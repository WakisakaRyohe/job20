<template>
	<header class="l-header">
		<div class="c-inner--l l-header__container">
			<div class="l-header__logoArea" :class="{'notClick': isSearch}">
				<!-- サイトタイトル -->
				<router-link :to="url" class="l-header__titleText" @click.native="deletaActive()">
					<h1 class="l-header__title"><i class="fa-solid fa-globe"></i><span>ジョブインフォ20</span></h1>
				</router-link>
			</div>
			
			<!-- メニューをスライド表示させた時の背景 -->
			<div class="l-header__bg" :class="{ active: isActive}" @click="deletaActive()"></div>

			<!-- ログイン済みユーザーのメニュー -->
			<div v-if="isLogin" class="l-header__authLinkArea" :class="{ active: isActive}">
				<ul class="l-header__menu" :class="{'l-header__menu--guest': !isLogin}">
					<li v-for="authNavLink in authNavLinks" :key="authNavLink.id"
						class="l-header__menuItem" :class="{ active: activeMenuNum === authNavLink.id }">
						<router-link :to="authNavLink.path" class="l-header__link" 
							:class="{ active: activeMenuNum === authNavLink.id }" @click.native="deletaActive()">
							<span v-html="authNavLink.icon"></span>{{ authNavLink.title }}
						</router-link>
					</li>
				</ul>
			</div>

			<!-- 未ログインユーザーのメニュー -->
			<div v-else class="l-header__guestLinkArea" :class="{ active: isActive}">
				<ul class="l-header__menu--guest">
					<li v-for="guestNavLink in guestNavLinks" :key="guestNavLink.id" 
						class="l-header__menuItem--guest" :class="{ active: activeMenuNum === guestNavLink.id }" >
						<router-link :to="guestNavLink.path" class="l-header__link--guest" 
							:class="[ { active: activeMenuNum === guestNavLink.id }, {'l-header__link--login': guestNavLink.id === 8} ]" @click.native="deletaActive()">
							<span v-html="guestNavLink.icon"></span>{{ guestNavLink.title }}
						</router-link>
					</li>
				</ul>
			</div>

			<!-- ハンバーガーメニュー -->
			<div class="l-header__toggleArea" :class="{ 'l-header__toggleArea--guest': !isLogin}">
				<div class="l-header__toggle" @click="reverseActive" :class="{ active: isActive}">
					<span class="l-header__toggleItem"></span>
					<span class="l-header__toggleItem"></span>
					<span class="l-header__toggleItem"></span>
				</div>
				<div class="l-header__toggleText">{{ menuText }}</div>
			</div>

		</div>
	</header>
</template>

<script>
export default {
	data(){
		return {
			isLogin: localStorage.getItem('isLogin'), // ログイン判定フラグ
			isActive: false, // ハンバーガーメニューの内容表示用フラグ
			text: '' ,
			url: '',
			authNavLinks: [ // ログイン済みユーザーのメニュー
				{
					id: 1,
					path: '/job20/search',
					title: '求人を探す',
					icon: '<i class="fa-solid fa-magnifying-glass"></i>',
				},
				{
					id: 2,
					path: '/job20/bookmark_jobs',
					title: '気になる求人',
					icon: '<i class="fa-regular fa-star"></i>',
				},
				{
					id: 3,
					path: '/job20/messages',
					title: 'メッセージ',
					icon: '<i class="fa-regular fa-envelope"></i>',
				},
				{
					id: 4,
					path: '/job20/web_resume',
					title: 'WEB履歴書',
					icon: '<i class="fa-regular fa-file-lines"></i>',
				},
				{
					id: 5,
					path: '/job20/setting',
					title: '各種設定',
					icon: '<i class="fa-solid fa-gear"></i>',
				},
				{
					id: 6,
					path: '/job20/logout',
					title: 'ログアウト',
					icon: '<i class="fa-solid fa-right-to-bracket"></i>',
				},

			],
			guestNavLinks: [ // 未ログインユーザーのメニュー
				{
					id: 7,
					path: '/job20/register',
					title: '会員登録',
					icon: '<i class="fa-solid fa-user"></i>',
				},
				{
					id: 8,
					path: '/job20/login',
					title: 'ログイン',
					icon: '<i class="fa-solid fa-right-to-bracket"></i>',
				},
			],
		}
	},

	methods: {
		// 横スライドメニューが出ていたら非表示にする
		handleResizeHeadder() {
			if(this.isActive === true){
				this.isActive = false;
			}
		},

		// ハンバーガーメニューがクリックされたら内容を表示
		reverseActive(){
			this.isActive = !this.isActive;
		},

		// スマホでメニューのリンクをクリックした時、遷移画面でメニューを非表示にする
		deletaActive(){
			if(this.isActive === true) this.isActive = false;
		},
	},

	computed:{
		menuText(){
			return (this.isActive) ? '閉じる' : 'メニュー' ;
		},
		activeMenuNum(){
			return this.$store.state.Header.activeMenuNum;
		},
		isSearch(){
			return this.$store.state.Header.isSearch;
		},
	},

	created(){
		this.url = (this.isLogin) ? '/job20/search/' : '/job20/' ;
		// イベント設定
		window.addEventListener('resize', this.handleResizeHeadder);
	},
	
	mounted(){
	},

	updated(){
	},

	beforeDestroy() {
		//EventListener削除
		window.removeEventListener('resize', this.handleResizeHeadder);
	},
}
</script>

<style scoped>
.notClick{
	pointer-events: none;
}
</style>