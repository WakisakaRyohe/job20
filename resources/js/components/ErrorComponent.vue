<template>
	<div>
		<!-- パンくずリスト -->
		<BreadCrumb :category="null" :title="catchText"></BreadCrumb>
		
		<div class="c-inner--l">
			<div class="c-pageSet">
				<div class="c-pageSet__navi--error u-mb0">
					<div class="c-pageSet__catch--alert"><i class="fa-solid fa-circle-exclamation"></i>
						<span class="c-pageSet__catchText">{{ catchText }}</span>
					</div>
					<div class="c-pageSet__copy" v-html="copyText"></div>
					<!-- ボタンエリア -->
					<div class="c-pageSet__btnArea u-mt20">
						<!-- <router-link class="c-btn--back" :to="referrer">前のページに戻る</router-link> -->
						<router-link v-if="isLogin" class="c-btn--m c-btn--subColor" :to="`/job20/search`">求人を探す</router-link>
						<router-link v-else class="c-btn--m c-btn--subColor" :to="{ name: 'login' }">ログイン</router-link>
						<!-- <router-link v-else class="c-btn--m c-btn--subColor" :to="{ name: 'login', params: { referrer: referrer } }">ログイン</router-link> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import BreadCrumb from '../components/BreadCrumb.vue';

export default {
	components: {
		"BreadCrumb": BreadCrumb,
	},

	props: [
		'catchText',
		'copyText',
	],

	data(){
		return {
			isLogin: true,
			referrer: this.$router.referrer['fullPath'],
		}
	},
	
	mounted(){
		// 選択中のメニューを変更
		this.$store.commit('Header/setActiveMenuNum', 0);

		// ログインユーザーかどうかで表示変更
		if(localStorage.getItem('isLogin') === 'true') {
			// console.log('ログインユーザー');
		}else{
			// console.log('未ログインユーザー');
			this.isLogin = false;
		}
	},
}
</script>
