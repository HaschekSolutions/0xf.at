<?php

class CreateView
{
	function test()
	{
		$html = new HTML;
		
		return '
				<div id="controls"></div>
				<style type="text/css" media="screen">
				    #editor { 
				        height: 100%;
					    min-height: 600px;
				    }
				</style>
				<pre id="editor"></pre>
				    
				<script src="/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
				<script src="/js/create.js" type="text/javascript" charset="utf-8"></script>
				<script>loadNew();</script>';
	}

}