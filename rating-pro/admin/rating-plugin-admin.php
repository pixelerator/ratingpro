<?php
if (isset($_POST['opot'])) {
	$a = $_POST['cat'];
	update_option("rater", $a);
	echo '<div class="alert alert-success">Settting has been updated"</div>';
}
?>
<?php

$selected_cat = get_option("rater");
$categories = get_categories();
?>

<div class="container">
	<div class="row">
		<div class="progress">
			<div class="bar" style="width: 100%;"></div>
		</div>
	</div>
	<div class="row">
		<div class="span3">
			<form method="post">
				<fieldset>
					<label>Visibility of rating plugin</label>
					<?php
					foreach ($categories as $category) {
						if ($category -> category_parent == 0) {
							if (is_array($selected_cat)) {
								if (in_array($category -> cat_ID, $selected_cat)) {
									echo '<label class="checkbox"><input type="checkbox" name="cat[]" checked="checked" value=' . $category -> cat_ID . '>' . $category -> cat_name . '</label>';
								} else {
									echo '<label class="checkbox"><input type="checkbox" name="cat[]" value=' . $category -> cat_ID . '>' . $category -> cat_name . '</label>';
								}
							} else {
								echo '<label class="checkbox"><input type="checkbox" name="cat[]" value=' . $category -> cat_ID . '>' . $category -> cat_name . '</label>';

							}
						}
					}
					?>
					<button type="submit" class="btn" value="submit" name="opot">
						Submit
					</button>
				</fieldset>
		</div>
	</div>
</div>
</form>