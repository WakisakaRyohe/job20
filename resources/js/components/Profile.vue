<template>
	<div class="c-form--borderTopNone">

		<!-- プロフィール登録済みの場合 -->
		<template v-if="profile">
			<h3 class="c-form__title">プロフィール</h3>

			<table class="c-table--showWebResume">
				<tbody>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">氏名</span>
						</th>
						<td class="c-table__td">{{ profile.last_name }} {{ profile.first_name }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">氏名カナ</span>
						</th>
						<td class="c-table__td">{{ profile.last_name_kana }} {{ profile.first_name_kana }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">生年月日</span>
						</th>
						<td class="c-table__td">{{ profile.year_of_birth }}年 {{ profile.month_of_birth }}月 {{ profile.date_of_birth }}日 ({{ profile.age }}歳)</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">性別</span>
						</th>
						<td class="c-table__td">{{ profile.sex }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">現住所</span>
						</th>
						<td class="c-table__td">
							<div class="u-mb10">〒{{ profile.zip }}</div>
							<div>{{ profile.prefecture }} {{ profile.municipalities }} {{ profile.other_address }}</div>
						</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">電話番号</span>
						</th>
						<td class="c-table__td">{{ profile.tel }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">最終学歴</span>
						</th>
						<td class="c-table__td">
							<div class="u-mb10">{{ profile.enrollment_year }}年 ~ {{ profile.graduation_year }}年 ({{ profile.graduation_type }})</div>
							<div class="c-table__flexWrap u-mb10">
								<span class="u-mr5">{{ profile.school_name }}</span><span>({{ profile.school_type }})</span>
							</div>
							<div class="c-table__flexWrap">
								<span class="u-mr5">{{ profile.faculty_and_department }}</span><span>({{ profile.literature_or_science }})</span>
							</div>
						</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">転職経験</span>
						</th>
						<td class="c-table__td">{{ profile.job_change_experience }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">現在の就業状況</span>
						</th>
						<td class="c-table__td">{{ profile.employment_status }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">直近の年収</span>
						</th>
						<td class="c-table__td">{{ profile.latest_annual_income }}</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">証明写真</span>
						</th>
						<td class="c-table__td">
							<img class="c-table__imageArea u-mb0" :src = "'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + profile.id_photo">
						</td>
					</tr>
				</tbody>
			</table>
		</template>

		<!-- プロフィール未登録の場合 -->
		<div v-else class="c-noData">
			プロフィールは<span class="u-spShow"><br></span>登録されていません。
		</div>

		<!-- ボタンエリア -->
		<div class="c-form__alignCenterArea">
			<router-link class="c-btn--m" :to="{ name: 'edit_profile', params: { propProfile: profile } }">
				<template v-if="profile">更新</template><template v-else>登録</template>画面へ進む</router-link>
		</div>
	</div>		
</template>

<script>
export default {
	props: [
		'profile',
	],
	data(){
		return {
		}
	},
	methods:{
	},
	computed:{
	},
}
</script>