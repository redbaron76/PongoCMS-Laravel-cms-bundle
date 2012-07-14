<?php
/*
   The CleanOut class was created by Jon Gjengset (jon@thesquareplanet.com)
   and may be used by anyone, anywhere as long as this notice is intact.
   If you're using this script for a large project, please send me an 
   e-mail with the URL so I can have a look =)
*/
class CleanOutput
{
	private $cleanhtml = '';
	private $uncleanedhtml = '';
	private $type = 'html';
	
	private $inlinehtmltags = array(	"a","basefont","bdo","font",
						"iframe","map","param","q",
						"span","sub","sup","abbr","acronym",
						"cite", "del", "dfn", "em", "kbd",
						"strong", "var", "b", "big", "i",
						"s", "small", "strike", "tt", "u",
						"span", "li", "label", "input"
					);
	
	private $tagswithtabsandnewlines = array(	"textarea",
							"pre",
							"code"
						);
	private $inlineregexp = '';
	private $inlineregexp2 = '';
	private $inlineregexp3 = '';
	private $inlineregexp4 = '';
	private $tabslist = '';
	
	//This function is neccessary for constucting the regular expression needed for inline elements
	function make_reg_exp()
	{
		$stam = "/(<(";
		$stam2 = "/([\w\d_\-=\"':&?+#%\;,\.\/*\s]*<\/(";
		$stam3 = "/[\n\s\r]*(<\/(";
		
		//Set up the list of inline tags, seperated by OR |
		$middle = implode('|', $this->inlinehtmltags);
		
		//Make the regexps
		$this->inlineregexp = $stam.$middle.")>)\n/sUi";
		$this->inlineregexp2 = $stam.$middle.")\s.*>)\n/sUi";
		$this->inlineregexp3 = $stam2.$middle.")>)/sUi";
		$this->inlineregexp4 = $stam3.$middle.")>)/sUi";
		
		$this->tabslist = implode('|', $this->tagswithtabsandnewlines);
	}
	
	//Function to seperate multiple tags one line
	function fix_newlines_for_clean_output($fixthistext)
	{
		$fixthistext = str_replace(">", ">\n", $fixthistext);
		$fixthistext = str_replace("<", "\n<", $fixthistext);
		
		$fixthistext_array = explode("\n", $fixthistext);
		$fixedtext_array = array();
		
		$inspecial = '';
		foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue)
		{
			//Makes sure empty lines are ignored
			if (!preg_match("/^(\s)*$/", $unfixedtextvalue) || $inspecial)
			{
				$fixedtext_array[$unfixedtextkey] = $unfixedtextvalue;
				
				if (preg_match("/<(".$this->tabslist.")/i", $unfixedtextvalue, $matches))
				{
					$inspecial = $matches[1];
				}
				else if (preg_match("/<\/$inspecial/i", $unfixedtextvalue))
				{
					$inspecial = '';
				}
			}
		}
		
		$fixedtext = implode("\n", $fixedtext_array);

		$fixedtext = preg_replace($this->inlineregexp, "$1", $fixedtext);
		$fixedtext = preg_replace($this->inlineregexp2, "$1", $fixedtext);
		$fixedtext = preg_replace($this->inlineregexp3, "$1", $fixedtext);
		$fixedtext = preg_replace($this->inlineregexp4, "$1", $fixedtext);
		
		//Fix special cases:
		$fixedtext = preg_replace("/<({$this->tabslist})>\n</sUi", "<$1><", $fixedtext);
		$fixedtext = preg_replace("/<({$this->tabslist})([^>]*)>\n</sUi", "<$1$2><", $fixedtext);
		$fixedtext = preg_replace("/\n<\/({$this->tabslist})>/sUi", "</$1>", $fixedtext);
		$fixedtext = preg_replace("/\n<\/({$this->tabslist})([^>]*)>/sUi", "</$1$2>", $fixedtext);
		
		//Fix <br /> tag
		$fixedtext = preg_replace("/\n(<br[\w\d_\-=\"':&?+#%\;,\.\/*\s]*>)/sUi", "$1", $fixedtext);
		return $fixedtext;
	}
	
	public $trace = FALSE;
	public $indent = "    ";
	
	function setXML()
	{
		$this->type = 'xml';
	}
	
	function process($uncleanhtml)
	{
		//Save unclean HTML
		$this->uncleanedhtml = $uncleanhtml;
		
		//Set wanted indentation and tracelines
		$indent = $this->indent;
		$showtrace = $this->trace;
		
		//Make RegExp for inline elements
		$this->make_reg_exp();
		
		//Uses previous function to seperate tags
		$fixed_uncleanhtml = $this->fix_newlines_for_clean_output($uncleanhtml);
		$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
		//Sets no indentation
		$indentlevel = 0;
		$inspecialcase = '';
		$inscript = FALSE;
		
		for ($uncleanhtml_key = 0; $uncleanhtml_key < count($uncleanhtml_array); $uncleanhtml_key++)
		{
			$currentuncleanhtml = $uncleanhtml_array[$uncleanhtml_key];
			
			//Removes all indentation
			$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
			$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);
			
			$replaceindent = "";
			
			//Sets the indentation from current indentlevel
			for ($o = 0; $o < $indentlevel; $o++)
			{
				$replaceindent .= $indent.($showtrace ? '|' : '');
			}
			
			if ($this->type == 'html')
			{
				if ($inspecialcase != '')
				{
					//Never apply indentation unless it's the end tag
					if (strpos($currentuncleanhtml, '</'.$inspecialcase) !== FALSE)
					{
						$inspecialcase = '';
						$cleanhtml_array[$uncleanhtml_key] = $currentuncleanhtml;
					}
					else
					{
						$cleanhtml_array[$uncleanhtml_key] = $currentuncleanhtml;
					}
				}
				else if ($inscript)
				{
					if (stripos($currentuncleanhtml, '</script') !== FALSE)
					{
						$inscript = FALSE;
						$indentlevel--;
						$replaceindent = "";
						for ($o = 0; $o < $indentlevel; $o++)
						{
							$replaceindent .= $indent.($showtrace ? '|' : '');
						}
					}
					else
					{
						if (stripos($currentuncleanhtml, '</script') !== FALSE)
						{
							$inscript = FALSE;
						}
						
						if (strrpos($currentuncleanhtml, '>') == (strlen($currentuncleanhtml)-1) && strlen($currentuncleanhtml) != 0)
						{
							$currentuncleanhtml = $currentuncleanhtml.'$';
						}
						
						if (strpos($currentuncleanhtml, '<') === 0)
						{
							$currentuncleanhtml = '$'.$currentuncleanhtml;
						}
						$currentuncleanhtml = str_replace('$<!--', '<!--', $currentuncleanhtml);
						$currentuncleanhtml = str_replace('//-->$', '//-->', $currentuncleanhtml);
					}
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				else
				{
					//Special case tag starts
					if (preg_match("/<(".$this->tabslist.")/i", $currentuncleanhtml, $matches))
					{
						if (strpos($currentuncleanhtml, '</'.$matches[1]) === FALSE)
						{
							$inspecialcase = $matches[1];
						}
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					}
					//Script starts
					if (preg_match("/<script/i", $currentuncleanhtml))
					{
						$inscript = TRUE;
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
						
						$indentlevel++;
						$replaceindent = "";
						for ($o = 0; $o < $indentlevel; $o++)
						{
							$replaceindent .= $indent.($showtrace ? '|' : '');
						}
					}
					//If self-closing tag, simply apply indent
					else if (preg_match("/^<([^<>]+)\/>/", $currentuncleanhtml))
					{ 
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					}
					//If doctype declaration or comment, simply apply indent
					else if (preg_match("/^<!(.*)>/", $currentuncleanhtml))
					{ 
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					}
					//If opening AND closing tag on same line, simply apply indent
					else if (preg_match("/^<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
					{ 
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					}
					//If closing HTML tag decrease indentation and then apply the new level
					else if (preg_match("/^<\/(.*)>/", $currentuncleanhtml))
					{
						$indentlevel--;
						$replaceindent = "";
						for ($o = 0; $o < $indentlevel; $o++)
						{
							$replaceindent .= $indent.($showtrace ? '|' : '');
						}
						
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					}
					//If opening HTML tag increase indentation and then apply new level
					else if (preg_match("/^<[^\/](.*?)>/", $currentuncleanhtml))
					{
						$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
						
						$indentlevel++;
						$replaceindent = "";
						for ($o = 0; $o < $indentlevel; $o++)
						{
							$replaceindent .= $indent.($showtrace ? '|' : '');
						}
					}
					else
					//Else, only apply indentation
					{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
				}
			}
			else
			{
				//If opening AND closing tag on same line, simply apply indent
				if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml))
				{ 
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				//If closing XML tag, decrease indentation and then apply the new level
				else if (preg_match("/<\/(.*)>/", $currentuncleanhtml))
				{
					$indentlevel--;
					$replaceindent = "";
					for ($o = 0; $o < $indentlevel; $o++)
					{
						$replaceindent .= $indent.($showtrace ? '|' : '');
					}
					
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
				}
				//If opening XML tag (and not opening XML tag), increase indentation and then apply new level
				else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<?(.*)>/", $currentuncleanhtml))
				{
					$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
					
					$indentlevel++;
					$replaceindent = "";
					for ($o = 0; $o < $indentlevel; $o++)
					{
						$replaceindent .= $indent.($showtrace ? '|' : '');
					}
				}
				else
				//Else, only apply indentation
				{$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;}
			}
		}
		
		$fixedtext = implode("\n", $cleanhtml_array);
		
		//Script fix
		$fixedtext = preg_replace('/>\$[\n\s]*\$</sU', '><', $fixedtext);
		$fixedtext = preg_replace('/>\$[\n\s]*/s', '>', $fixedtext); //Not ungreedy, since it's supposed to "eat" everything after $
		$fixedtext = preg_replace('/[\n\s]*\$</sU', '<', $fixedtext);
		$fixedtext = str_replace('{$', '{', $fixedtext);
		$fixedtext = str_replace('}$', '}', $fixedtext);
		
		//Return single string seperated by newline
		$this->cleanhtml = $fixedtext;
	}
	
	function set_lc()
	{
		if (empty($this->cleanhtml))
		trigger_error("Output has not yet been cleaned", E_USER_NOTICE);
		
		$this->cleanhtml = preg_replace("<(\/?)([^<>\s]+)>/Ue", "'<'.'\\1'.lc('\\2').'>'", $this->cleanhtml);
		$this->cleanhtml = preg_replace("<(\/?)([^<>\s]+)(\s?[^<>]+)>/Ue", "'<'.'\\1'.lc('\\2').'\\3'.'>'", $this->cleanhtml);
	}
	
	function show($return = FALSE, $highlight = FALSE)
	{
		if (empty($this->cleanhtml))
		trigger_error("Output has not yet been cleaned", E_USER_NOTICE);
		
		if ($return)
		{
			return ($highlight ? $this->highlight($this->cleanhtml) : $this->cleanhtml);
		}
		else
		{
			echo ($highlight ? $this->highlight($this->cleanhtml) : $this->cleanhtml);
		}
	}
	
	function show_before_clean($return = FALSE, $showcode = FALSE, $highlight = FALSE)
	{
		if ($return)
		{
			if ($showcode)
			{return ($highlight ? $this->highlight($this->uncleanedhtml) : htmlentities($this->uncleanedhtml));}
			else
			{return $this->uncleanedhtml;}
		}
		else
		{
			if ($showcode)
			{echo ($highlight ? $this->highlight($this->uncleanedhtml) : htmlentities($this->uncleanedhtml));}
			else
			{echo $this->uncleanedhtml;}
		}
	}
	
	function show_before_after($highlight = FALSE)
	{
		?>
		Before:<br />
		<pre style="border:1px solid black;padding:5px 5px 5px 5px;height:40%;width:90%;overflow:scroll;"><?php $this->show_before_clean(FALSE, TRUE, $highlight); ?></pre>
		<br />
		After:<br />
		<pre style="border:1px solid black;padding:5px 5px 5px 5px;height:40%;width:90%;overflow-x:scroll;"><?php $this->show(FALSE, TRUE, $highlight); ?></pre>
		<br />
		<?php
	}
	
	function ob_clean($messycode)
	{
		$this->process($messycode);
		return $this->cleanhtml;
	}
	
	function reset()
	{
		$this->trace = FALSE;
		$this->indent = "    ";
		$this->cleanhtml = '';
		$this->iscleaned = FALSE;
		$this->type = 'html';
	}
	
	function highlight($cont)
	{
		$ret = str_replace('&','&amp;',$cont);
		$ret = str_replace('"', '&quot;', $ret);
		$ret = preg_replace("/(<\/?)(.+)>/U", '<span style="color:#0000DD">&lt;\\2&gt;</span>', $ret);
		$ret = preg_replace("/=\&quot;(.*)\&quot;/U", '=&quot;<span style="color:#DD0000">\\1</span>&quot;', $ret);
		$ret = preg_replace("/(\s+)([\w-_]+)=&quot;/U", '\\1<span style="color:#00AA00;">\\2</span>=&quot;', $ret);
		return $ret;
	}
};

?>