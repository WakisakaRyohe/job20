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
			catchText: 'エラーが発生しました',
			copyText: '大変申し訳ございません。<br class="u-tabShow">時間を空けてもう１度お試しください。',
		}
	},
}
</script>