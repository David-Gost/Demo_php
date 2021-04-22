/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    config.toolbarGroups = [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'styles', groups: ['styles']},
        {name: 'colors', groups: ['colors']},
        {name: 'paragraph', groups: ['list', 'align', 'bidi', 'blocks', 'indent', 'paragraph']},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'clipboard', groups: ['undo', 'clipboard']},
        {name: 'links', groups: ['links']},
        {name: 'insert', groups: ['insert']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
        {name: 'forms', groups: ['forms']},
        '/',
        '/',
        {name: 'tools', groups: ['tools']},
        {name: 'others', groups: ['others']},
        {name: 'about', groups: ['about']}
    ];

    config.language = 'zh';
    config.width = '100%';
    config.height = '450'
//    config.removePlugins = 'about,a11yhelp';
    config.extraPlugins = 'image2,youtube,uploadfile,quicktable';
    config.skin = 'moonocolor';
//    config.removeButtons = '';
//	 config.uiColor = '#AADC6E';
};
