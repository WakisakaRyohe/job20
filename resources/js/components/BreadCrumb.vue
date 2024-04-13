<template>
	<div class="c-breadCrumb">
		<ul class="c-breadCrumb__list">
			<li class="c-breadCrumb__listItem">
				<router-link class="c-breadCrumb__link" :to="url">
					<span class="c-breadCrumb__text">ホーム</span>
				</router-link>
			</li>
			<li v-if="category" class="c-breadCrumb__listItem">
				<router-link :to="category.path" class="c-breadCrumb__link">
					<span class="c-breadCrumb__text">{{ category.name }}</span>
				</router-link>
			</li>
			<li  v-if="title" class="c-breadCrumb__listItem">
				<span class="c-breadCrumb__text">{{ title }}</span>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	props: [
		'category',
		'title',
	],
	data(){
		return {
			url: (localStorage.getItem('isLogin')) ? '/job20/search/' : '/job20/' ,
		}
	},
	methods: {
	},
	computed:{
	},

	mounted() {
	},
	updated(){
	}
}
</script>
