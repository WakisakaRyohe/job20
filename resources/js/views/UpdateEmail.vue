<template>
	<!-- ロード中 -->
	<Loading v-if="isLoading"></Loading>
</template>

<script>
import Loading from '../components/Loading.vue';

export default {
	components: {
		"Loading": Loading,
	},

	data(){
		return {
			isLoading: true,
		}
	},

	mounted(){
		// axiosで使うthisを格納
		const self = this;

		axios.get('/job20/web/complete_update_email')
		.then(function(response){
			// 遷移先でフラッシュメッセージ表示
			self.$store.commit('Modal/setFlash', true);

			if(response.data.successFlg){
				self.isLoading = false;

				// 正常に処理完了すれば、何もせずページ表示
				self.$store.commit('Modal/setMessage', 'メールアドレスを変更しました。');
				self.$router.push({ name: 'setting'})

				// vuexのデータ初期化
				self.$store.commit('ChangeEmail/setEmailOld', '');
				self.$store.commit('ChangeEmail/setEmailNew', '');
				self.$store.commit('ChangeEmail/setEmailConfirmation', '');

			}else{
				// 不正なリクエストでセッションキーがない場合（URL直打ちなど）
				if(response.data.unAuthFlg){
					self.$store.commit('Modal/setMessage', '不正なリクエストです。もう１度やり直してください。');
					self.$router.push({ name: 'setting'})
					return;

				// 有効期限切れの場合
				}else if(response.data.limitOverFlg){
					self.$store.commit('Modal/setMessage', 'メールアドレス変更の有効期限切れです。もう１度やり直してください。');
					self.$router.push({ name: 'setting'})
					return;
				}
			}
		});
	},
}
</script>