<h2>New Publisher</h2>
<form action="<?php echo base_url() ?>publishers/add" class="newPublisherForm" id="newPublisher">
    <div class="like-table">
        <div class="like-row">
            <div class="like-cell">
                Name:
            </div>
            <div class="like-cell">
                <input type="text" name="field-new-publisher-name"
                    class="field-new-publisher-name" maxlength="100" />
            </div>
        </div>
        <div class="like-row">
            <div class="like-cell">
                PRO (optional):
            </div>
            <div class="like-cell">
                <select name="field-new-publisher-pro" class="field-new-publisher-pro">
                    <option value=""></option>
                    {{#all_pros}}
                    <option value="{{ id }}">{{ name }}</option>
                    {{/all_pros}}
                </select>
            </div>
        </div>
        <div class="like-row">
            <div class="like-cell">
                <input type="submit" value="Save" name="save-publisher" />
            </div>
            <div class="like-cell">
            </div>
        </div>
    </div>
</form>
