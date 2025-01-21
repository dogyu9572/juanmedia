/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar = [
		['Undo', 'Redo', 'Replace'],
		['Font', 'FontSize'],
		['TextColor', 'BGColor'],
		['Bold', 'Underline', 'Italic', 'Strike', 'Superscript', 'Subscript'],
		['Image','Table', 'SpecialChar', 'Smiley'],
		['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
		['Blockquote', 'NumberedList', 'BulletedList'],
		['Link', 'Unlink','Maximize'],
		['Source']
	];

	config.height = 260;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.allowedContent = true;
	config.pasteFilter = null;
	//config.filebrowserImageUploadUrl = "/common/file_upload.php?type=images";
	//config.font_names = 'Noto sans CJK Medium;Noto sans CJK Demilight;Noto sans CJK Bold;Gotham Bold;Gotham Medium;Gotham Demilight;' + CKEDITOR.config.font_names;
	config.font_names = 'Noto Sans KR;나눔 스퀘어/NanumSquareRound;' + CKEDITOR.config.font_names;
};
