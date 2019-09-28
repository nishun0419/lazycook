<template>
    <div class="jumbotron col-md-12" v-show="show">
      <div class="conteiner">
        <div class="col-md-6 col-md-offset-3 login-form">
          <form action="#" method="#" v-on:submit.prevent="onSubmit">
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Password</label>
              <input type="password" class="form-control" id="InputPassword" placeholder="Enter Password">
            </div>
            <button type="submit" class="btn btn-primary">ログイン</button>
          </form>
        </div>
      </div>
    </div>
</template>

<script>
    export default {

      data() {
          return{
            show: true
          }
      },
      methods:{
        onSubmit() {
            this.show = false;
            this.$router.push('/todo');
        }
      }
    }
</script>
