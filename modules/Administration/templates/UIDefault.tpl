{*@todo: create labels for text...*}
<style>
    input.lookLikeDisabled {
        opacity:0.6;
        pointer-events: none;
    }
</style>
<div>
    <h1>UI load default settings</h1>
    <p>&nbsp;</p>
    <form name="UIDefaultConf" action="" method="POST">
        <h2>CAUTION! Following processes will be triggered:</h2>
        <ol>
            <li><strong>delete</strong> current UI settings stored in sysui tables
                <div>
                    {$sysuitableslist}
                </div>
            </li>
            <li>load default UI settings into sysui tables</li>
        </ol>
        <p>Custom configuration will not be affected.</p>
        <p>&nbsp;</p>
        {nocache}
        {if $hasOpenChangeRequest}
            <div class="error">
                <h2 class="error">Action cannot be started.</h2>
                <div>Uncompleted change request found.</div>
            </div>
        {else}

            <div>
                <div>Current Packages and versions</div>
                <table class="yui3-skin-sam edit view panelContainer">
                    <tbody>
                    <tr>
                        <td scope="col" width="12%"><label>Packages</label></td>
                        <td><ul>{foreach $currentpackages as $item}
                                    <li>{$item}</li>
                                {/foreach}
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td scope="col" width="12%"><label>Versions</label></td>
                        <td><ul>{foreach $currentversions as $item}
                                    <li>{$item}</li>
                                {/foreach}
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div>Settings for load</div>
                <table class="yui3-skin-sam edit view panelContainer">
                    <tbody>
                        <tr>
                            <td scope="col" width="12%"><label>Select Package(s)</label><span class="required">*</span></td>
                            <td>
                                <div id="packageslist">
                                {*<input name="uidefaultconf_package" value="*" type="text" required>*}
                                <input type="checkbox" id="selectAllP" checked="checked"/> select / deselect all<br/>
                                {foreach $possiblepackages as $package}
                                    <input type="checkbox" class="checkBoxClassP" name="packages[]" value="{$package.package}" checked="return false;"/> {$package.package} [{$package.description}]<br/>
                                {/foreach}
                                </div>
                                <div id="obsoletelist">
                                    {foreach $obsoletepackages as $package}
                                        <p class="error"><input type="checkbox" class="checkBoxClassO lookLikeDisabled" name="packages[]" value="{$package}" onclick="javascript: return false;" checked="checked" onclick="return false;" onkeydown="return false;"/> {$package} [obsolete - will be deleted]</p>
                                    {/foreach}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td scope="col"><label>Select Version(s)</label><span class="required">*</span></td>
                            <td>
                                <div id="versionslist">
                                    {*{if !$release}*}
                                        {*<input type="checkbox" id="selectAllV" checked="checked"/> select / deselect all<br/>*}
                                        <select name="versions[]">
                                        {foreach $possibleversions as $version}
                                            <option name="versions[]" value="{$version.version}"> {$version.version}</option>
                                        {/foreach}
                                        </select>

                                    {*{else}*}
                                        {*{foreach $possibleversions as $version}*}
                                            {*<input type="checkbox" class="checkBoxClassV " name="versions[]" value="{$version.version}" checked="checked"/> {$version.version}<br/>*}
                                        {*{/foreach}*}
                                    {*{/if}*}



                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <h2>Continue?</h2>
            <input name="uidefaultconf_process" value="1" type="hidden">
            <input name="uidefaultconf_btn" id="uidefaultconf_btn" value="YES" type="submit">
        {/if}
        {/nocache}

    </form>

    {literal}
        <script>
        $(document).ready(function () {
            $("#selectAllP").click(function () {
                $(".checkBoxClassP").prop('checked', $(this).prop('checked'));
            });
            // $("#selectAllV").click(function () {
            //     $(".checkBoxClassV").prop('checked', $(this).prop('checked'));
            // });
            $("#uidefaultconf_btn").click(function(){
                if($("#packageslist").find("input[type=checkbox]:checked").length == 0)
                {
                    alert("Please select at least one package");
                    return false;
                }
                // if($("#versionslist").find("input[type=checkbox]:checked").length == 0)
                // {
                //     alert("Please select at least one version");
                //     return false;
                // }
            });


        });
        </script>
    {/literal}
</div>