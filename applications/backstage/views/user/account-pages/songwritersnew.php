<h2>New Songwriter</h2>
<form action="<?php echo base_url() ?>songwriters/add" class="newSongwriterForm" id="newSongwriter">
    <div class="like-table">
        <div class="like-row">
            <div class="like-cell">
                Name:
            </div>
            <div class="like-cell">
                <input type="text" name="field-new-songwriter-name"
                    class="field-new-songwriter-name" maxlength="100" />
            </div>
        </div>
        <div class="like-row">
            <div class="like-cell">
                PRO (optional):
            </div>
            <div class="like-cell">
                <select name="field-new-songwriter-pro" class="field-new-songwriter-pro">
                    <option value=""></option>
                    {{#all_pros}}
                    <option value="{{ id }}">{{ name }}</option>
                    {{/all_pros}}
                </select>
            </div>
        </div>
        <div class="like-row">
            <div class="like-cell">
                Publisher (optional):
            </div>
            <div class="like-cell">
                <select name="field-new-songwriter-publisher" class="field-new-songwriter-publisher">
                    <option value=""></option>
                    {{#all_publishers}}
                    <option value="{{ id }}">{{ name }}</option>
                    {{/all_publishers}}
                </select>
            </div>
        </div>
        <div class="like-row">
            <div class="like-cell">
                <input type="submit" value="Save" name="save-songwriter" />
            </div>
            <div class="like-cell">
            </div>
        </div>
    </div>
</form>
