<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var \CBitrixComponent $component */
//$this->SetViewTarget("inner_tab_tab_bo_products");
?>

<? if($arParams["IS_AJAX_CALL"] != "Y"): ?>
	<style>
		.crm-items-table-header .crm-item-cell-text, .crm-item-table-txt, .crm-items-table-header .crm-item-cell {
			text-align: left!important;
		}
		.webform-small-button {
			margin: 0px;
		}
	</style>
	<script type="text/javascript">
		var inProgress = false;
		var wait = 0;

		function UpdateTable()
		{
			$.ajax({
				url: "<?= $this->GetFolder(); ?>/ajax.php",
				data: {
					OWNER_ID: '<?= $arParams["OWNER_ID"]; ?>',
					OWNER_TYPE: '<?= $arParams["OWNER_TYPE"] ?>'
				},
				type: "POST",
				success: function(data) {
					$('#bo-rows-container').html(data);
					inProgress = false;
					BX.closeWait('inner_tab_tab_bo_products', wait);
				},
				error: function (data) {
					inProgress = false;
					BX.closeWait('inner_tab_tab_bo_products', wait);
				}
			});
		}

		$(document).ready(function(){

			$(document).on('click', '#edit_rows_button', function(event){
				if ($(this).data('isEdit') == 0)
				{
					$(this).data('isEdit', 1);
					$(this).html('Сохранить');
					$('#product_table tr td.crm-item-design-price span.crm-item-cell-text').css('display', 'block');
					$('#product_table tr td.crm-item-design-price span.crm-item-cell-view').css('display', 'none');
				}
				else
				{
					if (inProgress) {
						return false;
					}
					inProgress = true;
					wait = BX.showWait('inner_tab_tab_bo_products');

					$(this).data('isEdit', 0);
					$(this).html('Редактировать');
					$('#product_table tr td.crm-item-design-price span.crm-item-cell-text').css('display', 'none');
					$('#product_table tr td.crm-item-design-price span.crm-item-cell-view').css('display', 'block');

					var designSum = 0;
					var arData = {};
					$('#product_table tr[data-row-id]').each(function(index){
						arData[index] = {
							BX_ROW_ID: $(this).data('rowId'),
							DESIGN_PRICE: $(this).find('input[name="design-price"]').val()
						};
						designSum += parseFloat($(this).find('input[name="design-price"]').val());
					});

					var arUpdProps = {};
					arUpdProps['<?= $arParams["PROP_DESIGN_SUM"]; ?>'] = designSum;
					arUpdProps['<?= $arParams["PROP_TOTAL_SUM"]; ?>'] = parseFloat(designSum) + parseFloat('<?= $arResult['TOTAL_SUM']; ?>');
					//console.log(arData);

					$.ajax({
						url: "<?= $this->GetFolder(); ?>/update_rows.php",
						data: {
							DATA_ROWS: arData,
							DEAL_ID: '<?= $arParams["OWNER_ID"]; ?>',
							DEAL_UPD_PROPS: arUpdProps
						},
						type: "POST",
						success: function(data) {
							var result = jQuery.parseJSON(data);
							if(result.SUCCESS != "ERROR")
							{
								UpdateTable();
							}
							else
							{
								alert(result.MESSAGE);
							}
						},
						error: function (data) {
							inProgress = false;
						}
					});
				}

			});
		});
	</script>
	<div id="bo-rows-container" class="crm-items-list-wrap crm-items-list-anim" data-tabs="">
<? endif; ?>

	<div class="crm-items-table-top-bar">

		<span class="crm-items-table-bar-r" style="float: right; margin: 10px 0px;">
			<span id="edit_rows_button" data-is-edit="0" class="webform-small-button">
				<span class="webform-small-button-left"></span>
				<span class="webform-small-button-text edit-rows">Редактировать</span>
				<span class="webform-small-button-right"></span>
			</span>
		</span>
	</div>
	<table id="product_table" class="crm-items-table" style="">
		<thead>
		<tr class="crm-items-table-header">
			<td class="crm-item-cell crm-item-name"><span class="crm-item-cell-text">Наименование</span></td>
			<td class="crm-item-cell crm-item-price"><span id="price_title" class="crm-item-cell-text">Цена (руб.)</span></td>
			<td class="crm-item-cell crm-item-qua"><span class="crm-item-cell-text">Кол-во</span></td>
			<td class="crm-item-cell crm-item-unit"><span class="crm-item-cell-text">Ед. измерения</span></td>

			<td class="crm-item-cell crm-item-total"><span class="crm-item-cell-text">Итого</span></td>

			<td class="crm-item-cell crm-item-total"><span class="crm-item-cell-text">Оценка дизайнера</span></td>
			<td class="crm-item-cell crm-item-total"><span class="crm-item-cell-text">Итого (с учетом дизайна)</span></td>

			<td class="crm-item-cell crm-item-move"><span class="crm-item-cell-text"></span></td>
		</tr>
		</thead>
		<tbody>

		<? foreach($arResult["PRODUCT_ROWS"] as $key => $arProduct): ?>
			<?
			//region prepare html params
			$htmlValues = array();
			$productID = intval($arProduct['PRODUCT_ID']);

			// PRODUCT_NAME
			$productName = isset($arProduct['PRODUCT_NAME']) ? $arProduct['PRODUCT_NAME'] : '';
			if($productName === '')
			{
				$productName = $productID > 0 && isset($arProduct['ORIGINAL_PRODUCT_NAME'])
					? $arProduct['ORIGINAL_PRODUCT_NAME'] : "[{$productID}]";
			}

			$fixedProductName = '';
			if ($productName == "OrderDelivery" || $productName == "OrderDiscount")
			{
				$fixedProductName = $productName;
				if ($productName == "OrderDelivery")
					$productName = GetMessage("CRM_PRODUCT_ROW_DELIVERY");
				elseif ($productName == "OrderDiscount")
					$productName = GetMessage("CRM_PRODUCT_ROW_DISCOUNT");
			}

			$htmlValues['PRODUCT_NAME'] = htmlspecialcharsbx($productName);

			// PRICE
			$htmlValues['PRICE'] = number_format(
				($arResult['ALLOW_TAX'] && $arResult['ENABLE_TAX']) ? $arProduct['PRICE_NETTO'] : $arProduct['PRICE_BRUTTO'],
				2, '.', ' '
			);

			// QUANTITY
			$htmlValues['QUANTITY'] = rtrim(rtrim(number_format($arProduct['QUANTITY'], 4, '.', ''), '0'), '.');

			$designPrice = $arProduct["DESIGN_INFO"]["DESIGN_PRICE"] > 0 ? $arProduct["DESIGN_INFO"]["DESIGN_PRICE"] : 0;
			$htmlValues['DESIGN_PRICE'] = number_format($designPrice, 2, '.', ' ');

			// SUM
			$htmlValues['SUM'] = number_format($arProduct['PRICE'] * $arProduct['QUANTITY'], 2, '.', ' ');

			// SUM
			$htmlValues['SUM_WITH_DESIGN'] = number_format($arProduct['PRICE'] * $arProduct['QUANTITY'] + $designPrice, 2, '.', ' ');
			//endregion
			?>
			<tr class="crm-items-table-odd-row" data-row-id="<?= $arProduct['ID']; ?>">
				<td class="crm-item-cell crm-item-name">
					<span class="crm-item-cell-view">
						<span class="crm-table-name-left">
							<span class="crm-item-move-btn view-mode"></span>
							<span class="crm-item-num"><?= $key +1; ?>.</span>
						</span>
						<span class="crm-item-txt-wrap">
							<div class="crm-item-name-txt"><?= $htmlValues["PRODUCT_NAME"] ?></div>
						</span>
					</span>
				</td>
				<td class="crm-item-cell crm-item-price">
					<span class="crm-item-cell-view">
						<div class="crm-item-table-txt"><?= $htmlValues["PRICE"]; ?></div>
					</span>
				</td>
				<td class="crm-item-cell crm-item-qua">
					<span class="crm-item-cell-view">
						<div class="crm-item-table-txt"><?= $htmlValues["QUANTITY"]; ?></div>
					</span>
				</td>
				<td class="crm-item-cell crm-item-unit">
					<span class="crm-item-cell-view">
						<div class="crm-item-table-txtl"><?= $arProduct["MEASURE_NAME"]; ?></div>
					</span>
				</td>

				<td class="crm-item-cell crm-item-total">
					<span class="crm-item-cell-view">
						<div class="crm-item-table-txt"><?= $htmlValues['SUM']; ?></div>
					</span>
				</td>

				<td class="crm-item-cell crm-item-design-price">
					<span class="crm-item-cell-text" style="display: none;">
						<input name="design-price" type="text" value="<?= $designPrice; ?>" class="crm-item-table-inp">
					</span>
					<span class="crm-item-cell-view">
						<div class="crm-item-table-txt"><?= $htmlValues['DESIGN_PRICE']; ?></div>
					</span>
				</td>
				<td class="crm-item-cell crm-item-total">
					<span class="crm-item-cell-view">
						<div class="crm-item-table-txt"><?= $htmlValues['SUM_WITH_DESIGN']; ?></div>
					</span>
				</td>
				<td></td>
			</tr>
		<? endforeach; ?>
		</tbody>
	</table>

	<div class="crm-view-table-total" style="margin-top: 10px;">
		<div class="crm-view-table-total-inner">
			<table>
				<tbody>
				<tr class="crm-view-table-total-value">
					<td>
						<nobr>Общая сумма:</nobr>
					</td>
					<td>
						<strong class="crm-view-table-total-value">
							<?= CCrmCurrency::MoneyToString($arResult['TOTAL_SUM'], $arResult['CURRENCY_ID']) ?>
						</strong>
					</td>
				</tr>
				<tr class="crm-view-table-total-value">
					<td>
						<nobr>Общая сумма (с учетом дизайна):</nobr>
					</td>
					<td>
						<strong class="crm-view-table-total-value">
							<?= CCrmCurrency::MoneyToString($arResult['TOTAL_WITH_DESIGN'], $arResult['CURRENCY_ID']) ?>
						</strong>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

<? if ($arParams["IS_AJAX_CALL"] != "Y"): ?>
	</div>
<? endif; ?>