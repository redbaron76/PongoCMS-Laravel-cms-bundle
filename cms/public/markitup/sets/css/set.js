// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// CSS set by Kevin Papst
// http://www.kevinpapst.de/
// Initially written for the BIGACE CMS:
// http://www.bigace.de/
// ----------------------------------------------------------------------------
// Basic CSS set. Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	onEnter:			{},
	onShiftEnter:		{keepDefault:false, placeHolder:'Your comment here', openWith:'\n\/* ', closeWith:' *\/'},
	onCtrlEnter:		{keepDefault:false, placeHolder:"classname", openWith:'\n.', closeWith:' { \n'},
	onTab:				{keepDefault:false, openWith:'  '},
	markupSet:  [	
		{name:'Class', className:'class', key:'N', placeHolder:'Properties here...', openWith:'.[![Class name]!] {\n', closeWith:'\n}'},
		{separator:'---------------' },
		{name:'Bold', className:'bold', key:'B', replaceWith:'font-weight:bold;'},
		{name:'Italic', className:'italic', key:'I', replaceWith:'font-style:italic;'},
		{name:'Stroke through',  className:'stroke', key:'S', replaceWith:'text-decoration:line-through;'},
		{separator:'---------------' },
		{name:'Lowercase', className:'lowercase', key:'L', replaceWith:'text-transform:lowercase;'},
		{name:'Uppercase', className:'uppercase', key:'U', replaceWith:'text-transform:uppercase;'},
		{separator:'---------------' },
		{name:'Text indent', className:'indent', openWith:'text-indent:', placeHolder:'5px', closeWith:';' },
		{name:'Letter spacing', className:'letterspacing', openWith:'letter-spacing:', placeHolder:'5px', closeWith:';' },
		{name:'Line height', className:'lineheight', openWith:'line-height:', placeHolder:'1.5', closeWith:';' },
		{separator:'---------------' },
		{name:'Alignments', className:'alignments', dropMenu:[
			{name:'Left', className:'left', replaceWith:'text-align:left;'},
			{name:'Center', className:'center', replaceWith:'text-align:center;'},
			{name:'Right', className:'right', replaceWith:'text-align:right;'},
			{name:'Justify', className:'justify', replaceWith:'text-align:justify;'}
			]
		},
		{name:'Padding/Margin', className:'padding', dropMenu:[
				{name:'Top', className:'top', openWith:'(!(padding|!|margin)!)-top:', placeHolder:'5px', closeWith:';' },
				{name:'Left', className:'left', openWith:'(!(padding|!|margin)!)-left:', placeHolder:'5px', closeWith:';' },
				{name:'Right', className:'right', openWith:'(!(padding|!|margin)!)-right:', placeHolder:'5px', closeWith:';' },
				{name:'Bottom', className:'bottom', openWith:'(!(padding|!|margin)!)-bottom:', placeHolder:'5px', closeWith:';' }
			]
		},
		{separator:'---------------' },
		{name:'Background Image', className:'background', replaceWith:'background:url([![Source:!:http://]!]) no-repeat 0 0;' },
		{separator:'---------------' },
		{name:'Import CSS file',  className:'css', replaceWith:'@import "[![Source file:!:.css]!]";' }
	]
}
