// Data members
const baseUrl = document.body.dataset.baseUrl
const googleRecaptchaSiteKey = document.body.dataset.googleRecaptchaSiteKey
const googleClientId = document.body.dataset.googleClientId
const eLoading = document.querySelector(".loading")
const eForm = document.forms[0];
const eSubmitter = document.querySelector("button[type=submit]");
const output = document.querySelector("output");

/**
 * Form
 */
const form = () => {
    document.forms[0].addEventListener("submit", async (event) => {
        event.preventDefault()
       // grecaptcha.ready(async() => {
           // grecaptcha.execute(googleRecaptchaSiteKey, { action: 'submit' }).then(async (token) => {
                // Add your logic to submit to your backend server here.
                const formData = new FormData(eForm, eSubmitter);

                const email = formData.get('email'),
                    password = formData.get('password'),
                    frmToken = formData.get('toekn'),
                    googleRecaptchaToken = token

                if (email.length === 0 || email.length > 30) {
                    eForm.email.focus()
                    output.textContent = 'ایمیل را صحیح وارد کنید'
                    return false
                }

                if (password.length === 0 || password.length > 50) {
                    output.textContent = 'پسورد را صحیح وارد کنید'
                    return false
                }

                loading(true)

                var myHeaders = new Headers();
                myHeaders.append("Content-Type", "text/plain");

                var requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: `{"email":"${email}","password":"${password}","googleRecaptchaToken":"${googleRecaptchaToken}"}`,
                    redirect: 'follow'
                };

                await fetch(`${baseUrl}admin/auth`, requestOptions)
                    .then(response => response.json())
                    .then(result => {
                        console.log(result)
                        if (result.result) {
                            sessionStorage.setItem("loginDate", new Date().getUTCDate().toString());
                            setCookie("token", result.token)
                            setCookie("admin_info", JSON.stringify(result.admin_info))
                            console.log(result.admin_info.admin_id)
                            location.replace(result.message);
                        } else {
                            alert(result.message);
                            window.location.reload(true);
                            loading(false);
                        }

                    })
                    .catch(error => console.log('error', error));
           // })
      //  })
    })
}

/**
 * Loading
 * @param {} state 
 */
const loading = (state = false) => {
    eLoading.style.opacity = state ? "1" : "0"
    eLoading.style.visibility = state ? "visible" : "hidden"
    // loadingElement.remove()
}

/**
 * 
 * @param {object} response 
 */
const handleCredentialResponse = (response) => {
    console.log("Encoded JWT ID token: " + response.credential);
}

const toggleLoginButton = () => {
    if (document.forms[0].elements.namedItem('email').value.length === 0 || document.forms[0].elements.namedItem('password').value.length === 0)
        document.forms[0].elements.namedItem('btnSubmit').disabled = true
    else
        document.forms[0].elements.namedItem('btnSubmit').disabled = false
}

const changelog = async ()=>{
    const response = await fetch(`${baseUrl}v1/changelog`)

    if (!response.ok) {
        throw new Response('Failed to fetch links', { status: 500 })
    }
    return response.json()
}

/**
 * Initializing
 */
window.addEventListener("load", async() => {
    // Init Google Sign In
    google.accounts.id.initialize({
        client_id: googleClientId,
        callback: handleCredentialResponse
    });
    google.accounts.id.prompt(); // also display the One Tap dialog

    loading(false)
    form()

    changelog().then(result => {
       document.querySelector('#changelog').innerHTML = result.content
    })

    document.forms[0].elements.namedItem('email').addEventListener("keyup", async (event) => {
        toggleLoginButton()
    })
    document.forms[0].elements.namedItem('password').addEventListener("keyup", async (event) => {
        toggleLoginButton()
    })
})

function javascriptCallback() {
    console.log('run')
}

function setCookie(cname, cvalue, exdays = 30) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie() {
    let username = getCookie("username");
    if (username != "") {
        alert("Welcome again " + username);
    } else {
        username = prompt("Please enter your name:", "");
        if (username != "" && username != null) {
            setCookie("username", username, 365);
        }
    }
}