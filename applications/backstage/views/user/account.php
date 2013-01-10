<script id="template-account-page" type="text/template">
<h2>{{target_user.full_name}}</h2>

	<div class="tabs clearfix" id="accountTabs">
	    <ul class="nav clearfix">
		    <li><span class="anchor" data-href="#accountContactsPage"><span>Contacts</span></span></li>
			<li><span class="anchor" data-href="#accountPaymentPage"><span>Payment</span></span></li>
			<li><span class="anchor" data-href="#accountDocumentsPage"><span>Documents</span></span></li>
			<li><span class="anchor" data-href="#accountPasswordPage"><span>Password</span></span></li>
		
			{{#if user.isAdmin}}
			<li><span class="anchor" data-href="#accountArtistsPage"><span>Artists</span></span></li>
			<li><span class="anchor" data-href=""><span>Royalties</span></span></li>
			{{/if}}
		</ul>
		<div id="accountContactsPage" class="tab clearfix">
			<?php $this->load->view('user/account-pages/contacts'); ?>
		</div>
		<div id="accountPaymentPage" class="tab clearfix">
			<?php $this->load->view('user/account-pages/payment'); ?>
		</div>
		<div id="accountDocumentsPage" class="tab clearfix">
			<?php $this->load->view('user/documents'); ?>
		</div>
		<div id="accountPasswordPage" class="tab clearfix">
			<?php $this->load->view('user/account-pages/password'); ?>
		</div>
		<div id="accountArtistsPage" class="tab clearfix">
			<?php $this->load->view('artist/include'); ?>
		</div>
		
		<?php /*
		<div id="accountSongwritersPage" class="tab clearfix">
			<?php $this->load->view('user/account-pages/songwriters'); ?>
		</div>
		<div id="accountPublishersPage" class="tab clearfix">
			<?php $this->load->view('user/account-pages/publishers'); ?>
		</div>
		<div id="accountProsPage" class="tab clearfix">
			<?php $this->load->view('user/account-pages/pros'); ?>
		</div>
		
		<div id="accountSongwritersnewPage" class="hideable-pages">
		    <?php $this->load->view('user/account-pages/songwritersnew.php'); ?>
		</div>
	    <div id="accountPublishersnewPage" class="hideable-pages">
	        <?php $this->load->view('user/account-pages/publishersnew.php'); ?>
	    </div>
	    <div id="accountPronewPage" class="hideable-pages">
	        <?php $this->load->view('user/account-pages/pronew.php'); ?>
	    </div>
		*/ 
		?>
	
	</div>
</script>

<script id="template-publishers-row-display" type="text/template">
    <div class="like-row" id="publisher-{{ publisher.id }}">
      <input type="hidden" value="{{ publisher.id }}" />
      <div class="like-cell" class="disp-publisher-name"> {{ name }}</div>
      <div class="like-cell" class="disp-publisher-pro"> {{ pro.name }}</div>
      <div class="like-cell" class="disp-publisher-edit"> <a href="#" class="edit">Edit</a> </div>
    </div>
</script>

<script id="template-pro-row-display" type="text/template">
    <div class="like-row" id="pro-{{ id }}">
      <input type="hidden" value="{{ id }}" />
      <div class="like-cell" class="disp-pro-name">
          {{#if new_pro}}
              {{ new_pro.name }}
          {{else}}
              {{ name }}
          {{/if}}
      </div>
      <div class="like-cell" class="disp-pro-edit"> <a href="#" class="edit">Edit</a> </div>
    </div>
</script>

<script id="template-publishers-row-edit" type="text/template">
  <div class="like-row">
      <div class="like-cell">
          <form action="<?php echo base_url() ?>publishers/delete/{{ id }}">
              <img src="<?php echo base_url()?>static/images/icon-delete-small.png" id="delete-publisher-{{ songwriter.id }}" class="delete like-link"/>
          </form>
      </div>
    <form action="<?php echo base_url(); ?>publishers/update" class="form-edit">
      <input type="hidden" name="field-publisher-id" value="{{ id }}"
        class="field-publisher-id" />
      <input type="hidden" name="field-publisher-pro-id" value="{{ pro.id }}"
        class="field-publisher-pro-id" />
        <div class="like-cell" id="songwriter-field">
            <input type="text" name="field-publisher-name"
              class="field-publisher-name" value='{{ name }}'>
        </div>
        <div class="like-cell" id="pro-field">
            <select name="field-publisher-pro" class="field-publisher-pro">
                <option value="" >Select PRO</option>
                {{#all_pros}}
                <option value="{{ id }}" >{{ name }}</option>
                {{/all_pros}}
            </select>
        </div>
        <div class="like-cell" id="save-field">
            <input type="submit" value="Save" name="save-publisher" class="like-submit" id="save-publisher" />
        </div>
    </form>
    </div>
</script>

<script id="template-pro-row-edit" type="text/template">
  <div class="like-row">
      <div class="like-cell">
          <form action="<?php echo base_url() ?>pro/delete/{{ id }}">
              <img src="<?php echo base_url()?>static/images/icon-delete-small.png" id="delete-pro-{{ id }}" class="delete like-link"/>
          </form>
      </div>
    <form action="<?php echo base_url(); ?>pro/update" class="form-edit">
      <input type="hidden" name="field-pro-id" value="{{ id }}"
        class="field-publisher-pro-id" />
        <div class="like-cell" id="songwriter-field">
            <input type="text" name="field-pro-name"
              class="field-pro-name" value='{{ name }}'>
        </div>
        <div class="like-cell" id="save-field">
            <input type="submit" value="Save" name="save-pro" class="like-submit" id="save-pro" />
        </div>
    </form>
    </div>
</script>

<script id="template-songwriters-new" type="text/template">
  <form action="<?php print base_url()?>songwriters/add">
    <div class="like-row form-row auto-text" id="songwriter-add">
      <div class="like-cell">
        <input name="songwriter-new-name" type="text" class="containsHint" alt="Name..." value="Name..." maxlength=100 id="songwriter-new-name" />
      </div>
      <div class="like-cell">
        <input type="text" class="containsHint" name="songwriter-new-pro" alt="PRO..." value="PRO..." maxlength=100 />
      </div>
      <div class="like-cell">
        <select name="songwriter-new-publisher" id="field-songwriter-publisher">
          <option value=""> </option>
          {{#all_publishers}}
            <option value="{{ id }}">{{ name }}</option>
          {{/all_publishers}}
        </select>
      </div>
      <div class="like-cell">
        <input type="submit" value="Create" class="create" name="create" />
      </div>
    </div>
    <div class="like-row row-add" id="add-songwriter-button">
      <a href="#" class="add">+ Add Songwriter</a>
    </div>
  </form>
</script>
