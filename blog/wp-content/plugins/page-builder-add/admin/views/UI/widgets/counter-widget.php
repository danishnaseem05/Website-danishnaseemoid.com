<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="tabs" style="width: 100%;">
  <ul class="tab-links">
    <li class="active"><a href="#cf1" class="tab_link">Counter</a></li>
    <li><a href="#cf2" class="tab_link">Style</a></li>
  </ul>
<div class="tab-content" style="box-shadow:none;">
	<div id="cf1" class="tab active" style="background: #fff; padding:20px 10px 20px 25px; width: 99%;">
	   <br>
		<br>
        <label>Starting Number: </label>
        <input type="number" class="counterStartingNumber">
        <br><br><hr><br>
        <label>Ending Number: </label>
        <input type="number" class="counterEndingNumber">
        <br><br><hr><br>
        <label>Number Prefix: </label>
        <input type="text" class="counterNumberPrefix">
        <br><br><hr><br>
        <label>Number Suffix: </label>
        <input type="text" class="counterNumberSuffix">
        <br><br><hr><br>
        <label>Animation Time: </label>
        <input type="number" class="counterAnimationTime" min="500" >
        <br><br><hr><br>
        <label>Title: </label>
        <input type="text" class="counterTitle" >
        <br><br><hr><br>
	</div>
	<div id="cf2" class="tab" style="background: #fff; padding:20px 10px 20px 25px; width: 99%;">
		<br>
        <label>Text Color :</label>
		<input type="text" class="color-picker_btn_two counterTextColor" id="counterTextColor" value='#333333'>
		<br><br><hr><br>
		<label>Title Color :</label>
		<input type="text" class="color-picker_btn_two counterTitleColor" id="counterTitleColor" value='#333333'>
		<br><br><hr><br>
		<label>Counter Font Size : </label>
        <input type="number" class="counterNumberFontSize">
        <br><br><hr><br>
		<label>Title Font Size : </label>
        <input type="number" class="counterTitleFontSize">
        <br><br><hr><br>
	</div>
</div>
</div>