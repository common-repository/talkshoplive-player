jQuery.ajax({
    method: 'GET',
    url: TalkShopLivePlayer.siteUrl + '/wp-json/talkshoplive/v1/settings',
    beforeSend: function ( xhr ) {
        // xhr.setRequestHeader( 'X-WP-Nonce', TalkShopLivePlayer.api.nonce );
    }
}).then( function ( r ) {

});

console.log("loaded script");
