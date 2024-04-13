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
			catchText: 'ページ閲覧にはログインが必要です',
			copyText: 'お手数ですが、ログイン後に再度アクセスしてください。',
		}
	},
}
</script>
