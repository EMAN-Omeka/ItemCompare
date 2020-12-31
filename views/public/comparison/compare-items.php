<?php
echo head(array('bodyclass' => 'items compare', 'title' => 'Comparaison des notices'));
?>
<script type="text/javascript" src="<?php echo WEB_ROOT; ?>/plugins/ItemCompare/javascripts/ajax.js"></script>
<link href="<?php echo WEB_ROOT; ?>//plugins/ItemCompare/css/style.css" media="all" rel="stylesheet" type="text/css" >
<link href="<?php echo WEB_ROOT; ?>/application/views/scripts/css/jquery-ui.css" media="all" rel="stylesheet" type="text/css" >
<h1>Comparaison de notices</h1>
<hr />
<div id="tout">
<input type="text" id="listitemleft" placeholder="Tapez une partie d'un titre de notice" />
<input type="text" id="listitemright" placeholder="Tapez une partie d'un titre de notice"  />
<div id='itemleft'>
<?php echo $left;  ?>
</div>
<div id='itemright'>
<?php echo $right;  ?>  
</div>
</div>
<input type="button" id="pleinecran" value="Plein écran" />
<div id="over" style="opacity: 1 !important;background-color:#eee;">
  <input id="fermer" type="button" value="X"/>
</div>

<script>
$ = jQuery;
$('#pleinecran').click(function() {
  $('#over').css("display", "block"); 
  $('#over').css("opacity", "1");      
  tout = $('#tout').detach();
  tout.prependTo('#over');
});
$('#fermer').click(function() {
  tout = $('#tout').detach();
  tout.appendTo('#content');  
  $('#over').css("display", "none"); 
  $('#over').css("opacity", "1");   
}); 
</script>

<style>
input, select, textarea, div {
} 
#over {
  display:none;
  z-index:100;
  position:absolute;
  width:100%;
  top:0;
  left:0;
  background: #eee;
  background-color: rgba(0, 0, 255, 1);
  opacity: 1 !important;
  filter:alpha(opacity=1) !important;
  -moz-opacity:1 !important;
    
}
#fermer {
  position:absolute;
  right:0;
  top:0;
}
#pleinecran {
  clear:both;
}
 
#content {
/*   position:relative; */
}
  .wrap {
    display:block;
    vertical-align: top;
    clear:left;
    float:left;
  }
  #itemleft {
    float:left;
    clear:left;
    width: 49%;
    display:block;
  }
  #itemright {
    float:right;
    clear:right;
    width: 49%;
    display:block;
    margin-top:0;
  }
  #listitemleft  {
    clear:left;
    float:left;
    width:49%;
    display:inline;    
  }
  #listitemright  {
    clear:right;
    float:right;
    width:49%;
    display:inline;
  }
</style>
<input type='hidden' id='phpWebRoot' value="<?php echo WEB_ROOT; ?>" />

<?php
echo foot();