<template>
	<ErrorComponent :catchText="catchText" :copyText="copyText"></ErrorComponent>
</template>

<script>
import ErrorComponent from '../components/ErrorComponent.vue';

export default {
	components: {
		"ErrorComponent": ErrorComponent,
	},
	data(){
		return {
			catchText: 'ログイン有効期限切れです',
			copyText: 'お手数ですが、もう１度ログインしてください。',
		}
	},
	mounted(){
		// ヘッダー表示変更のため１回だけリロード
		if (window.name != "any") {
			location.reload();
			window.name = "any";
		} else {
			window.name = "";
		}
	}
}
</script>
