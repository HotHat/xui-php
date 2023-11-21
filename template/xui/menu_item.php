<a-menu-item key="/xui/">
    <a-icon type="dashboard"></a-icon>
    <span>系统状态</span>
</a-menu-item>
<a-menu-item key="/xui/inbounds">
    <a-icon type="user"></a-icon>
    <span>入站列表</span>
</a-menu-item>
<a-menu-item key="/xui/setting">
    <a-icon type="setting"></a-icon>
    <span>面板设置</span>
</a-menu-item>
<!--<a-menu-item key="{{ .base_path }}xui/clients">-->
<!--    <a-icon type="laptop"></a-icon>-->
<!--    <span>客户端</span>-->
<!--</a-menu-item>-->
<a-sub-menu>
    <template slot="title">
        <a-icon type="link"></a-icon>
        <span>其他</span>
    </template>
    <a-menu-item key="https://github.com/hothat/xui-php">
        <a-icon type="github"></a-icon>
        <span>Github</span>
    </a-menu-item>
</a-sub-menu>
<a-menu-item key="/logout">
    <a-icon type="logout"></a-icon>
    <span>退出登录</span>
</a-menu-item>