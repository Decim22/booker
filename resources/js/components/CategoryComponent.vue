<template>
<div>




<div v-for="category in categoryList">
    <p>{{category.id}} {{category.name}}</p>
</div>





</div>
</template>

<script>
    export default {
        data: function(){
          return {
            categoryList:[],
            category:{
              id:'',
              name:'',
              created_at:'',
              updated_at:'',
              }
          }
        },
        mounted: function(){
          console.log('Category Component Loaded...');
          this.fetchCategoryList();
        },
        methods: {
            fetchCategoryList:function(){
            console.log('Fetching categories...');
            axios.get('/api/categories')
              .then((response) => {
                console.log(response.data);
                this.categoryList = response.data;
              }).catch((error) => {
                console.log(error);
              });
            },
        }
    }








</script>