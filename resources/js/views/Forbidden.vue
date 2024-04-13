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
			catchText: 'ページの閲覧権限がありません',
			copyText: '管理者または他ユーザーのみ閲覧できるページです。',
		}
	},
}
</script>
