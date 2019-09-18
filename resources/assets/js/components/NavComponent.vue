<template>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarEexample7">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    タイトル
                </a>
             </div>
            <div class="collapse navbar-collapse" id="navbarEexample7">
                <ul class="nav navbar-nav">
                    <li><a href="#">メニューＡ</a></li>
                    <li class="active"><a href="#">メニューＢ</a></li>
                    <li><a href="#">メニューＣ</a></li>
                </ul>
                <p class="navbar-text navbar-right">ようこそ <a href="#" class="navbar-link" v-show="show">ゲスト</a> さん。</p>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        data() {
          return{
            show: true
          }
        }
    }
</script>