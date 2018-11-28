/**
 * Get value from the document cookie
 *
 * @param string name Name of the variable to retrieve
 * @return mixed
 */
function cookieGetTRM(name)
{
    name = name + "=";
    var cookies = document.cookie.split(';');
 
    for (var i = 0; i < cookies.length; i++) {
        var c = cookies[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1, c.length);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
 
    return null;
}



/**
 * Store value in the document cookie
 *
 * @param string name     Name of the variable to store
 * @param string value    Value of the variable
 * @param integer seconds Number of seconds after which the cookie will be invalid
 * @param string path     Path for which the cookie will be valid
 */
function cookieSetTRM(name, value, seconds, path)
{
    var expires = "";
   
    if (seconds) {
        var date = new Date();
        date.setTime(date.getTime() + (seconds * 1000));
        expires = ";expires=" + date.toGMTString();
    }
 
    path = ";path=" + path;
 
    document.cookie = name + "=" + value + expires + path;
}


/**
 * Delete value from the document cookie
 *
 * @param string name Name of the variable to delete
 */
function cookieDeleteTRM(name)
{
    cookieSet(name, "", -1);
}