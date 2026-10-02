Greenlink approved-stock correction handover report

Prepared: 2 October 2026 (Asia/Colombo). Application area: Goods Received Note (GRN).

**Purpose and scope.** The GRN list now lets a user click an Approved status button to reveal the available stock actions for that record. Quantity corrections apply to all eligible approved GRNs, including receipts linked to a purchase order (PO). Each successful correction updates inventory by the quantity difference, recalculates receipt totals, and records adjustment history.

The working copy already contained the stock-adjustment modal, endpoints, permission checks, audit logging, and transaction handling before the latest extension. The latest work generalized that implementation beyond one configured opening-stock GRN, changed the action trigger to the Approved button, added PO-specific bookkeeping, and added regression tests. This report describes the resulting feature so a developer or business user can understand its behavior without the chat history.

**Previous behavior and current behavior.** The earlier correction implementation checked a configured `existing_stock_grn_id` and restricted quantity editing to receipts with `grn_source = no_po`. Its extra edit/history buttons therefore appeared for one designated GRN. The latest implementation removes the ID restriction from the view, controller, model, and configuration.

| Area | Previous implementation | Current implementation |
| --- | --- | --- |
| Eligible receipts | One configured GRN | Any approved GRN whose record status is 1 or 2 |
| Receipt source | Quantity corrections limited to no-PO receipts | Both no-PO and PO receipts |
| Action visibility | Inline icons on the designated approved row | Click Approved to reveal the row's icons; click again to hide them |
| Quantity correction permission | GRN edit permission | GRN edit permission, checked in the browser and on the server |
| Linked PO accounting | Correction path did not handle PO receipts | Received quantities and completion status updated with the correction |
| Feature configuration | Enable flag plus one GRN ID | Enable flag only |

**Using the feature.** Follow these steps on the Goods Received Note page:

1. Find an approved receipt in the GRN list.
2. Click its green Approved button. That row's Actions cell expands.
3. Choose View to inspect the normal GRN details, Edit quantities to correct accepted quantities, or Adjustment history to inspect previous corrections.
4. In Edit quantities, review the item, batch, previous quantity, corrected quantity, and adjustment difference.
5. Enter the corrected quantities. The screen displays positive or negative differences as the values change.
6. Enter a correction reason. The initial value is `Approved stock correction` and can be replaced with a more specific explanation.
7. Click Save quantities. The save button is disabled while the request runs.
8. On success, the list reloads while preserving its current page, and the modal reloads the updated quantities and history.

The History action opens the same modal in read-only mode. Quantity inputs are disabled, and the reason and Save quantities controls are hidden. Users without edit permission can inspect history when they have GRN access permission. Opening the actions or the modal alone does not change inventory.

The edit action corrects accepted receipt quantities. It does not provide an approved-record form for changing suppliers, locations, unit prices, or adding/removing material lines. Pending receipts continue to use their existing form and action buttons.

**How inventory is calculated.** The key calculation is:

```text
adjustment = corrected accepted quantity - previous accepted quantity
new stock quantity = current stock quantity + adjustment
new batch quantity = current batch quantity + adjustment
new batch balance = current batch balance + adjustment
```

The current stock balance can include later issues or other receipts. The correction therefore applies only the difference; it does not replace that balance with the receipt quantity or approve the receipt again.

| Example | Previous accepted quantity | Corrected quantity | Adjustment | Stock before | Stock after |
| --- | ---: | ---: | ---: | ---: | ---: |
| Increase | 10.00 | 12.50 | +2.50 | 15.00 | 17.50 |
| Reduction | 10.00 | 8.00 | -2.00 | 15.00 | 13.00 |
| No change | 10.00 | 10.00 | 0.00 | 15.00 | 15.00 |

The reduction is rejected if it would make stock, batch quantity, or batch balance negative. For example, reducing a receipt by 5.00 is rejected when the remaining batch balance is only 3.00. A save with no quantity differences succeeds without adding adjustment-log rows.

Quantity calculations use integer hundredths internally: `10.25` becomes `1025`. This avoids floating-point drift when calculating differences and balances. Quantities must be non-negative and have at most two effective decimal places. Values with additional trailing zeros are accepted. The changed line total is rounded to two decimal places using accepted quantity multiplied by the existing unit price; the GRN total is the sum of its line totals.

**No-PO receipts and PO receipts.** For a no-PO receipt, `qty`, `po_qty`, `received_qty`, and `accepted_qty` are set to the corrected quantity, while `rejected_qty` is set to zero.

For a PO receipt, the implementation preserves the GRN line's existing `qty`, `po_qty`, and `rejected_qty`. It changes `accepted_qty` and reconciles the line's `received_qty` as corrected accepted quantity plus existing rejected quantity. This retains the ordered and rejected quantities instead of treating the PO receipt like an opening-stock entry.

The linked `tbl_porder_detail.received_qty` is cumulative accepted receipt accounting. It changes by the same adjustment used for stock. The resulting value must be between zero and that PO line's ordered quantity. The implementation identifies the PO line by purchase-order ID and material ID and requires exactly one matching line. Missing or duplicate matches are rejected.

After a successful change, PO completion is recalculated across all PO lines. Reducing a completed receipt can set `completedstatus` back to 0. Restoring the received quantities sets it to 1 when every line is fully received. A PO remains incomplete if another line still has an outstanding quantity.

**Records affected by a successful correction.** Updates and the corresponding history insert occur within one database transaction.

| Table | Effect |
| --- | --- |
| `tbl_grndetail` | Corrected accepted quantity, relevant receipt quantity fields, line total, and update timestamp |
| `tbl_grn` | Recalculated total and update timestamp; approval remains approved |
| `tbl_stock` | Existing material/site/warehouse balance changes by the adjustment; status becomes 1 for a positive balance or 0 for zero |
| `tbl_batchstock` | Existing matching batch quantity and balance change by the adjustment; status follows whether batch balance is positive |
| `tbl_stock_adjustment_log` | One history row for each changed GRN detail |
| `tbl_porder_detail` | For PO receipts, cumulative received quantity changes by the adjustment |
| `tbl_porder` | For PO receipts, completion status is recalculated |

Each audit row contains the GRN ID, GRN-detail ID, material ID, site, warehouse, batch, old and new accepted quantities, signed adjustment, stock before and after, reason, user ID, and timestamp. The action is `Manually Added` for an increase or `Manually Reduced` for a reduction. History is returned newest first and displays the user's name where available. The controller uses the Asia/Colombo timezone.

**Permissions and validation.** The actual permission lookup requires a logged-in session with a user ID and an active `tbl_user_privilege` record for menu ID 14 with GRN access enabled. Quantity corrections additionally require the edit privilege.

The global setting is in `application/config/config.php`:

```php
$config['existing_stock_edit_enabled'] = TRUE;
```

Setting it to `FALSE` hides the approved quantity-edit icon and causes the model to reject correction attempts. View and history remain available to users with access permission. The removed `existing_stock_grn_id` setting is no longer used in this feature. Existing method and CSS names containing `ExistingStock` remain for compatibility with the current implementation; they now serve all eligible approved receipts.

The server validates the following conditions before committing:

- The GRN exists, remains approved, and has record status 1 or 2.
- The feature is enabled and the user has edit permission.
- The submitted item list is nonempty, contains no duplicate detail IDs, and matches the stored receipt's detail set.
- Each detail belongs to the selected GRN, and its submitted previous quantity matches the database value.
- Every quantity has the supported format and precision.
- The reason is nonempty and no longer than 255 bytes on the server; the browser input limits entry to 255 characters.
- Exactly one existing stock row and one matching batch row can be identified.
- Stock and batch values remain non-negative.
- For PO receipts, the linked PO exists, the relevant material has exactly one PO line, and its adjusted received balance stays within the ordered quantity.

The previous-quantity check detects a receipt changed since the user loaded the modal. The user must reload rather than overwrite that newer value. Relevant GRN, detail, inventory, batch, and PO records are read with `FOR UPDATE` inside the transaction. A failed update, invalid balance, or failed audit insert rolls the transaction back. These guarantees depend on the live tables using a transactional database engine.

The existing `RequirePendingGrn()` guard continues to protect normal form editing, deletion, and approval operations. Approved quantity corrections use the dedicated adjustment path.

**Request flow and endpoint contract.** The browser sends AJAX requests to the existing Goodreceive controller. No external service is involved.

| Endpoint | Browser request | Response |
| --- | --- | --- |
| `Goodreceive/GetExistingStockAdjustmentData` | POST with `grn_id` | `status`, `grn` including details, `editable`, and `history` |
| `Goodreceive/UpdateExistingStockQuantities` | POST with `grn_id`, `items` as a JSON string, and `reason` | `status` and a result/error `message` |

The `items` value includes every receipt detail, even unchanged lines. Each item carries a detail ID, the accepted quantity originally loaded, and the requested new accepted quantity. For example:

```json
[
  { "detail_id": "3", "old_qty": "10.00", "qty": "12.50" }
]
```

The read endpoint returns HTTP 403 for denied access and 404 for a missing or ineligible approved receipt. The write endpoint requires POST, returning 405 for other methods and 403 when edit permission is missing. Model validation failures are returned as JSON with `status: 0`, normally with HTTP 200. Frontend callers must inspect that status rather than treating HTTP success alone as a successful correction.

**Where to maintain the implementation.** These are the relevant files in the current workspace:

| File | Responsibility |
| --- | --- |
| [application/views/goodreceive.php](../application/views/goodreceive.php) | Approved status toggle, per-row action visibility, edit/history modal, adjustment preview, and AJAX load/save |
| [application/controllers/Goodreceive.php](../application/controllers/Goodreceive.php) | Access checks, approved-record validation, POST enforcement on writes, and JSON responses |
| [application/models/Goodreceiveinfo.php](../application/models/Goodreceiveinfo.php) | Eligibility, quantity validation, locked transactional updates, PO synchronization, totals, and history |
| [application/config/config.php](../application/config/config.php) | Global approved-stock editing flag |
| [tests/approved_stock_adjustments_test.php](../tests/approved_stock_adjustments_test.php) | Automated regression checks using an in-memory database double |
| [scripts/goodreceivelist.php](../scripts/goodreceivelist.php) | Existing server-side list data; already returns GRN ID, source, and approval status, so no change was required |

Key model methods are `HasGrnPermission()`, `CanEditExistingStock()`, `GetExistingStockHistory()`, and `UpdateExistingStockQuantities()`. Quantity helpers are `ExistingStockUnits()` and `ExistingStockDecimal()`. The view's main modal loader is `loadExistingStock()`.

The status and action renderers are DataTables columns 8 and 9. The Approved button targets a Bootstrap collapse element named `grn-actions-<GRN ID>` in the matching Actions cell. Each row therefore toggles independently. Existing jQuery and Bootstrap 4 scripts provide the interaction; no new frontend package was added.

Other locally modified files, including `application/config/database.php`, `index.php`, and `scripts/config.php`, were already modified before this task. This report does not attribute those environment changes to the approved-stock feature.

**Verification completed.** PHP syntax checks passed for the changed view, controller, model, and configuration. JavaScript syntax and renderer checks passed for the Approved button's matching collapse target, initially hidden actions, both receipt sources, different GRN IDs, edit restrictions, and pending-row behavior. Whitespace checks passed for the feature files.

The regression test passed and can be run from the project root:

```powershell
php tests/approved_stock_adjustments_test.php
```

Its checks cover arbitrary approved GRN IDs, both receipt sources, denied edits, disabled editing, pending/deleted receipts, inventory and batch differences, receipt totals, PO received totals, reopening/completing POs, preserving ordered/rejected quantities, stale data, unsupported precision, excessive reductions, missing/ambiguous PO data, failed audit inserts, rollback behavior, no-change saves, and read-endpoint access behavior.

The test uses an in-memory database double. It does not access the application database. Permission lookup and some read methods are replaced by test doubles, so passing tests do not establish that the live privilege table, SQL schema, or database locks work correctly. The JavaScript checks exercise rendering and syntax; a browser interaction test was not performed.

**Database dependencies and practical limits.** This work did not add a database migration. The existing `tbl_stock_adjustment_log` table must contain the fields used by the model, including the `id` column used to sort history. The deployed inventory and PO tables must match the referenced columns. Their live schema and storage engines were not verified during this work.

The correction method currently has no warehouse/rack maximum-capacity check. It enforces non-negative balances and PO quantity limits. It also requires a uniquely identifiable existing stock and batch record; it does not repair missing or duplicate inventory records. It adjusts current balances and the receipt's totals without rewriting later issue/return records or creating a separate accounting entry.

**Suggested handover acceptance checks.** Use a test database with representative records before relying on the feature in a live workflow:

1. Verify that the audit table exists, the required columns are present, and the affected tables support transactions and row locks.
2. Log in as a user with GRN edit access. Confirm that clicking Approved toggles only the selected row's actions.
3. Increase and reduce a no-PO receipt quantity. Check the GRN total, stock, batch balance, and logged user/reason/difference.
4. Reduce a completed PO receipt. Check that cumulative received quantity decreases and the PO reopens; restore it and check completion again.
5. Verify that ordered and rejected PO quantities remain intact and another outstanding PO line keeps the order incomplete.
6. Use a user with access but no edit privilege. Confirm history is readable and corrections are denied by the server.
7. Attempt an excessive batch reduction, a stale save from another session, and a PO over-receipt. Confirm rejection with no partial updates.
8. Disable the edit flag. Confirm that quantity editing is blocked while history remains accessible.

These acceptance checks are recommendations for the receiving person; they were not performed against a live browser or database in this session.
