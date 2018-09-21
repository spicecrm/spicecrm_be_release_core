<link rel="stylesheet" type="text/css" href="modules/KOrgObjects/css/korgobjects.css">
<script type="text/javascript" src="modules/KOrgObjects/javascript/editKOrgObjects.js"></script>
<input type="hidden" id="primary_korgobjectid" name="primary_korgobjectid" value="{$pimarykorgobjectid}">
<input type="hidden" id="secondary_korgobjectids" name="secondary_korgobjectids" value='{$secondarykorgobjectids}'>
<table class="edit view" border="0" width="100%"><tbody>
<tr>
<td width="12.5%" valign="top" scope="row">primary Orgunit:</td>
<td  width="37.5%" valign="top">
<div id="primaryorgunit" class='korgobjectsinput'>
<div id='orgunitinput' onclick="korgObjects.toggleSearch();"  >{$orgunitinput}</div>
<!-- img src='modules/KOrgObjects/images/arrowdown.gif' -->

<div id='orgunitsearchdivs'>
<div id='orgunitsearch' onclick="korgObjects.toggleInput();" class='korgobjectssearch'>{$orgunitsearch}</div>
<div id='orgunitresults'  class='korgobjectssearch'>{$orgunitresults}</div>
<div id='orgunitfooter'  class='korgobjectsfooter'>
<input type="button" class="button" value="Cancel" onClick="korgObjects.hideSearch();">
</div>
</div>
</div>

</td>
<td width="12.5%" valign="top" scope="row">Addusers:</td><td width="37.5%" valign="top">xxx</td>
</tr>
<tr>
<td width="12.5%" valign="top" scope="row">additional Orgunit:</td>
<td  width="37.5%" valign="top">
<div id="addorgunitcontainer">
{$addorgunits}
</div>
<div id="addorgunit" class='korgobjectsinput'>
<div id='addorgunitinput' onclick="korgObjects.toggleAddSearch();"  >{$orgunitaddinput}</div>
</div>
</td>
<td width="12.5%" valign="top" scope="row"></td><td width="37.5%" valign="top"></td>
</tr>
</tbody></table>