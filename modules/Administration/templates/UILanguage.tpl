{*@todo: create labels for text...*}
<div>
    <h1>UI load default languages</h1>
    <p>&nbsp;</p>
    <form name="UILanguageConf" action="" method="POST">
        <h2>CAUTION! Following processes will be triggered:</h2>
        <ol>
            <li><strong>delete</strong> current records stored
                in syslanguagelabels and syslanguagetranslations tables<br/>
                Custom labels will remain intact.
            </li>
            <li>load default language labels and translations into tables</li>
        </ol>
        <p>&nbsp;</p>

        {nocache}
        {if $hasOpenChangeRequest}
            <div class="error">
                <h2 class="error">Action cannot be started.</h2>
                <div>Uncompleted change request found.</div>
            </div>
        {else}
            <div>
                <div>Current Languages, Packages and versions</div>
                <table class="yui3-skin-sam edit view panelContainer">
                    <tbody>
                    <tr>
                        <td scope="col" width="12%"><label>Languages</label></td>
                        <td><ul>{foreach $currentlanguages as $item}
                                    <li>{$item}</li>
                                {/foreach}
                            </ul>
                        </td>
                    </tr>
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
                        <td scope="col" width="12%"><label>Select Language(s)</label><span class="required">*</span></td>
                        <td>
                            <div id="languageslist">
                                {*<input name="uidefaultconf_version" value="*" type="text" required>*}
                                <input type="checkbox" id="selectAllL" checked="checked"/> select / deselect all<br/>
                                {foreach $possiblelanguages as $language}
                                    <input type="checkbox" class="checkBoxClassL" name="languages[]" value="{$language.language_code}" checked="checked"/> {$language.language_name}<br/>
                                {/foreach}
                            </div>
                    </tr>
                    <tr>
                        <td scope="col" width="12%"><label>Enter Package<br>(get them all for now)</label><span class="required">*</span></td>
                        <td><input name="packages[]" value="*" type="text" required readonly="readonly"></td>
                    </tr>
                    <tr>
                        <td scope="col"><label>Enter Version<br>(get them all for now)</label><span class="required">*</span></td>
                        <td><input name="version[]" value="*" type="text" required readonly="readonly"></td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <h2>Continue?</h2>
            <input name="uilanguageconf_process" value="1" type="hidden">
            <input name="uilanguageconf_btn" value="YES" type="submit">
        {/if}
        {/nocache}

    </form>
</div>