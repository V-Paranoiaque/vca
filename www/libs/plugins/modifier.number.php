<?php

function smarty_modifier_number($text='') {
	return '';
}

function smarty_modifier_numberDiskSpace($size) {
	return numberDiskSpace($size);
}

function smarty_modifier_numberRamSize($size) {
	return numberRamSize($size);
}

function smarty_modifier_numberRamSizeCurrent($size) {
	return numberRamSizeCurrent($size);
}

function smarty_modifier_numberSwapSize($size) {
	return numberSwapSize($size);
}

function smarty_modifier_numberOrUnlimited($num) {
	return numberOrUnlimited($num);
}

?>
