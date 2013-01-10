<script id="template-playlists-view" type="text/template">

    <div id="playlists">
        <h2>Playlists</h2>

        <div class="like-table">
            <div class="like-header">
                <div class="like-header-cell">Title</div>
            </div>

            <div class="content"></div>

            <div class="like-row row-add" id="add-songwriter-button">
                <div class="like-cell">
                    <a href="#" class="add">Create Playlist</a>
                </div>
            </div>
        </div>
    </div>

</script>


<script id="template-playlist-row-view" type="text/template">

    <div class="like-row row-add">
        <div class="like-cell">
            <a class="title" href="#">{{ title }}</a>
        </div>

        <input type="hidden" name="id" value="{{ id }}" />

        <div class="like-cell">
            <a class="edit" href="#">edit</a>
        </div>

        <div class="like-cell">
            <a class="set-active" href="#">mark as active</a>
        </div>
    </div>

</script>


<script id="template-playlist-row-edit" type="text/template">

    <div class="like-row row-add">
        <form action="/playlists/edit" method="post">
            <div class="like-cell">
                <input type="text" name="title" value="{{ title }}" placeholder="Playlist name" />
            </div>

            <input type="hidden" name="id" value="{{ id }}" />

            <div class="like-cell">
                <input type="submit" value="Submit" />
            </div>
        </form>
    </div>

</script>
