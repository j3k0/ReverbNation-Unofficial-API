var PROXY_URL = 'reverbnation.php';
var gReverbNation = {user:'none', password:'none' };

function rnInitLabel(labelID) {
    gReverbNation['pageType'] = 'label';
    gReverbNation['labelID'] = labelID;
}
function rnInitArtist(artistID) {
    gReverbNation['pageType'] = 'artist';
    gReverbNation['artistID'] = artistID;
}

function rnIsLoggedIn() {
    if (typeof(gReverbNation['loggedin']) == 'undefined') return false;
    return (gReverbNation['loggedin']);
}

function rnLogin(user, password) {
    gReverbNation['user'] = user;
    gReverbNation['password'] = password;
}

function _rnPageRequest(request) {
    if (gReverbNation['pageType'] == 'label')
        request["init_label"] = gReverbNation['labelID'];
    if (gReverbNation['pageType'] == 'artist')
        request["init_artist"] = gReverbNation['artistID'];
    return request;
}

function _rnLogin(success_callback, error_callback) {
    if (typeof(gReverbNation['loggedin']) != 'undefined') {
        if (gReverbNation['loggedin']) {
            if (success_callback != null)
                success_callback();
        }
        else if (error_callback != null)
            error_callback();
        return;
    }
    var request = {
        "user[login]":gReverbNation['user'],
        "user[password]":gReverbNation['password'],
        "remember_me":"1",
        "commit":"Sign In",
        "Cancel":"Cancel",
        "_":""
    }
    request = _rnPageRequest(request);
    jQuery.post(PROXY_URL + "?action=login", request, function(data,textStatus) {
        jQuery('#rn-login-result').html(data);
        if (data == "true") {
            gReverbNation['loggedin'] = true;
            if (success_callback != null)
                success_callback();
        }
        else {
            gReverbNation['loggedin'] = false;
            if (error_callback != null)
                error_callback();
        }
    }, 'text');
}

function _rnStatus() {
    var request = {
        "title":"",
        "from":"",
        "in_control_room":"true",
        "status_update[message]":gReverbNation['status'],
        "status_update[post_twitter]":"0",
        "status_update[post_fb_connect]":"0",
        "status_update[post_myspace]":"0",
        "status_update[post_reverbnation]":"1",
        "_":""
    }
    request = _rnPageRequest(request);
    jQuery.post(PROXY_URL + "?action=status", request, function(data,textStatus) {
                jQuery('#rn-status-result').html(data);
    }, 'text');
}

function rnStatus(newStatus) {
    gReverbNation['status'] = newStatus;
    _rnLogin(_rnStatus, null);
}

function _rnAddShow() {
    var request = {
        'date': gReverbNation['showtime'],
        'venue[name]': gReverbNation['venue[name]'],
        'venue[city]': gReverbNation['venue[city]'],
        'venue[country]': gReverbNation['venue[country]'],
        'venue[address]': '',
        'venue[postal_code]': '',
        'show_bill_artists[0][id]': '',
        'show_bill_artists[0][display_order]': '0',
        'show_bill_artists[0][artist_id]': gReverbNation['artistID'], 
        'show_bill_artists[0][artist_name]': '', // TODO Set Artist Name
        'show_bill[private]':0,
        'show_bill[age_limit]':0
    }
    request = _rnPageRequest(request);
    jQuery.post(PROXY_URL + "?action=addshow", request, function(data,textStatus) {
                jQuery('#rn-addshow-result').html(data);
    }, 'text');
}

// showtime = "2010-09-06 14:13"; // YYYY-MM-JJ hh:mm
// venue_country is a two letter country code
function rnAddShow(showtime, venue_name, venue_city, venue_country) {
    gReverbNation['showtime'] = showtime;
    gReverbNation['venue[name]'] = venue_name;
    gReverbNation['venue[city]'] = venue_city;
    gReverbNation['venue[country]'] = venue_country;
    _rnLogin(_rnAddShow, null);
}
