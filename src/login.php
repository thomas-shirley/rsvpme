<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/tachyons.css"/>
	<link rel="stylesheet" href="../css/main.css" />
    <title>Login</title>
</head>
<body class="pa3 sans-serif center w-50-l min-vh-100 center flex flex-column justify-center">
    <header>
        <h1 class="f1">Login</h1>
    </header>
    <section id="app">
        <main>
            <form>
                <input type="password" class=" w-100 pa1 pv2 ba b--black-30 br2 " @input="reloadText($event)" v-model="$store.state.requestPayload.userpassword" type="password"/>
</br>
                <button class="f4 pa2 bg-near-white ba b--black-30  br3  mv3" @click="checkCredentials($event)">Login</button>
                <p v-if="$store.state.requestFailure">Incorrect</p>
            </form>
        </main>
    </section>
</body>


<!-- Vue JS scripts-->
<script src="../js/vue.js"></script>
<script src="../js/vuex.js"></script>
<script src="../js/vue-router.js"></script>
<!-- End Vue JS scripts-->

<script>




    const store = new Vuex.Store({
        state : {
            requestInProgress   : false,
            requestFailure      : false,
            requestPayload : {
                    request        : 'validateUser',
                    userpassword    : ''
                },
        },
        mutations : {
            //function forces flushing of state.
            UPDATE_TEXT_FIELD : function(input) {},
            SET_SENDING_STATE : function(state){
                if (state.requestsInProgressCount < 1){
                    state.requestInProgress = !state.requestInProgress;
                }
                else {
                    state.requestInProgress = true;
                }
            },
            SET_REQUEST_FAILURE : function() {
                store.state.requestFailure = true;
            }
        },
        actions : {
            CHECK_LOGIN : function() {
                store.commit("SET_SENDING_STATE");
                let formData = new FormData();
                formData.append('requestPayload', JSON.stringify(store.state.requestPayload));
                store.commit("SET_SENDING_STATE");
                let result = fetch('/src/controller.php', {
                        method  : 'POST',
                        mode    : 'cors',
                        body    : formData
                }).then(e => {return e.json()})
                  .then(e =>{
                      e.result ? window.location.assign('/admin') : store.commit("SET_REQUEST_FAILURE");
                  });    
                store.commit("SET_SENDING_STATE");
            }
        }
    })

    const app = new Vue({
            el : "#app",
            store,
            data : {

            },
            methods : {
                reloadText : function(input){
                store.commit("UPDATE_TEXT_FIELD", input);
                },
                checkCredentials : function(event){
                    event.preventDefault();
                    //Guard against submitting empty fields.
                    if (store.state.requestPayload.useremail == '' || store.state.requestPayload.userpassword == '') {return false;}
                    store.dispatch("CHECK_LOGIN");
                }
            }
        });

</script>
</html>


