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
			catchText: 'お探しのページが見つかりません',
			copyText: 'ページが存在しないか、一時的に閲覧できない可能性があります。',
		}
	},
}
</script>
