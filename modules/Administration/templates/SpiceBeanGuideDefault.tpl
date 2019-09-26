{*@todo: create labels for text...*}

<style>
    {foreach $spicebeanguidemodules as $spicebeanguidemodule => $spicebeanguidemodulename}
    .{$spicebeanguidemodule}{
        display:none;
    }
    {/foreach}
</style>

<script>
    function toggleDetailsForModule(module, checked){
        if(document.getElementsByClassName(module)){
            if(checked){
                if(document.getElementsByClassName(module).length > 0)
                    document.getElementsByClassName(module)[0].style.display = 'block';
            }else{
                if(document.getElementsByClassName(module).length > 0)
                    document.getElementsByClassName(module)[0].style.display = 'none';
            }
        }
    }

</script>

<div class="bd">
    <h1>SpiceBeanGuides load default settings</h1>
    {if $displayForm > 0}
        <form name="SpiceBeanGuideDefault" action="" method="POST">
            <table class="other view">
                <tr>
                    <td width="20%" scope=""row">Select a module</td>
                    <td>{foreach $spicebeanguidemodules as $spicebeanguidemodule => $spicebeanguidemodulename}
                            <input type="checkbox" class="checkBoxClassP" name="spicebeanguidemodules[]" value="{$spicebeanguidemodule}" onclick="toggleDetailsForModule('{$spicebeanguidemodule}', this.checked)"/> {$spicebeanguidemodulename}<br/>
                        {/foreach}

                        {*<select name="spicebeanguidemodules[]" >*}
                            {*{html_options options=$spicebeanguidemodules}*}
                        {*</select>*}
                    </td>
                </tr>
                <tr>
                    <td>Only clear the guide for selected module(s)</td>
                    <td> <input type="checkbox" name="spicebeanguideclearonly" value="1" />
                    </td>
                </tr>
            </table>

            <h2>CAUTION!</h2>
            <div class="other view"> Existing spicebean guides records will be cleared!</div>

            <div class="Opportunities">
                <h3>Good to know about Opportunities</h3>
                <div class="other view">
                    In case you already have opportunities with a value for sales_stage and probability,<br />
                    you will have to update those values in opportunities table with the corresponding value set in spice bean guide.<br />
                    Examples:
                    <ul>
                        <li>sales stage "Needs Analysis" has to be changed to "Analysis"</li>
                        <li>sales stage "Id. Decision Makers" no longer exists in default opportunity spice bean guide</li>
                    </ul>
                </div>

                <div>
                <pre>
                Spicebeanguide sales stages and probability:
                    Qualification => 0
                    Analysis => 25
                    Proposition => 45
                    Proposal => 60
                    Negotiation => 85
                    Closed Won => 100
                    Closed Lost => 0
                    Closed Discontinued => 0
                </pre>
                </div>
            </div>

            <div class="ServiceTickets">
                <h3>Good to know about ServiceTickets</h3>
                <div class="other view">This module is not available in SpiceCRM free version</div>
            </div>
            <div class="SystemDeploymentCRs">
                <h3>Good to know about SystemDeploymentCRs</h3>
                <div class="other view">This module is not available in SpiceCRM free version</div>
            </div>

            <div>
                <h2>Continue?</h2>
                <input name="spicebeanguidedefault_process" value="1" type="hidden">
                <input name="spicebeanguidedefault_btn" value="YES" type="submit">
            </div>
        </form>
    {/if}
    {if !$displayForm}
        <p>&nbsp;</p>
        <p>{$displayResults}</p>
        <p>&nbsp;</p>
        <h3>Please, go to Administration and trigger a <a href="index.php?module=Administration&action=Upgrade">Quick Repair and Rebuild!</a></h3>
    {/if}
</div>
