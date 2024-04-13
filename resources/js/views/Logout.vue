<template>
	<!-- ロード中 -->
	<Loading></Loading>
</template>

<script>
import Loading from '../components/Loading.vue';

export default {
	components: {
		"Loading": Loading,
	},

	props: [
		'loginExpired',
	],

	data(){
		return {
		}
	},
	
	methods: {
		// ログアウト処理
		logout: async function (){
			// ローカルストレージの全ての値を削除(axiosの前に行う)
			localStorage.clear();

			// axiosで使うthisを格納
			const self = this;

			await axios.post('/job20/logout')
			.then(response => {
				if(self.loginExpired){
					// ログイン有効期限切れページへ遷移
					self.$router.push({ name: 'loginExpired' });
				}else{
					// トップページへリダイレクト
					location.href = '/job20/';
				}
			}).catch(error => console.log(error) );
		},
	},

	computed:{
	},

	created(){
		// ログアウト処理
		this.logout();
	},
}
</script>
