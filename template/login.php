<!DOCTYPE html>
<html lang="en">
<?php render("common/head.php"); ?>
<style>

    #app {
        padding-top: 100px;
    }

    h1 {
        text-align: center;
        color: #fff;
        margin: 20px 0 50px 0;
    }

    .ant-btn, .ant-input {
        height: 50px;
        border-radius: 30px;
    }

    .ant-input-affix-wrapper .ant-input-prefix {
        left: 23px;
    }

    .ant-input-affix-wrapper .ant-input:not(:first-child) {
        padding-left: 50px;
    }

</style>
<body>
<a-layout id="app" v-cloak>
    <transition name="list" appear>
        <a-layout-content>
            <a-row type="flex" justify="center">
                <a-col :xs="22" :sm="20" :md="16" :lg="12" :xl="8">
                    <h1><?php e(renderEnv('title')) ?></h1>
                </a-col>
            </a-row>
            <a-row type="flex" justify="center">
                <a-col :xs="22" :sm="20" :md="16" :lg="12" :xl="8">
                    <a-form>
                        <a-form-item>
                            <a-input v-model.trim="user.username" placeholder='用户名'
                                     @keydown.enter.native="login" autofocus>
                                <a-icon slot="prefix" type="user" style="color: rgba(0,0,0,.25)"/>
                            </a-input>
                        </a-form-item>
                        <a-form-item>
                            <a-input type="password" v-model.trim="user.password"
                                     placeholder='密码' @keydown.enter.native="login">
                                <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)"/>
                            </a-input>
                        </a-form-item>
                        <a-form-item>
                            <a-button block @click="login" :loading="loading">登录</a-button>
                        </a-form-item>
                    </a-form>
                </a-col>
            </a-row>
        </a-layout-content>
    </transition>
</a-layout>
<?php render('common/js.php'); ?>
<script>
    const leftColor = RandomUtil.randomIntRange(0x222222, 0xFFFFFF / 2).toString(16);
    const rightColor = RandomUtil.randomIntRange(0xFFFFFF / 2, 0xDDDDDD).toString(16);
    const deg = RandomUtil.randomIntRange(0, 360);
    const background = `linear-gradient(${deg}deg, #${leftColor} 10%, #${rightColor} 100%)`;
    document.querySelector('#app').style.background = background;
    const app = new Vue({
        delimiters: ['[[', ']]'],
        el: '#app',
        data: {
            loading: false,
            user: new User(),
        },
        methods: {
            async login() {
                this.loading = true;
                const msg = await HttpUtil.post('/login/submit', this.user);
                this.loading = false;
                if (msg.success) {
                    location.href = basePath + 'xui/';
                }
            }
        }
    });
</script>
</body>
</html>