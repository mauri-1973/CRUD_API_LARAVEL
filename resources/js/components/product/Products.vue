<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{translate('lang.adm13')}}</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                     {{translate('lang.adm14')}}
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>{{translate('lang.adm15')}}</th>
                      <th>{{translate('lang.adm16')}}</th>
                      <th>{{translate('lang.adm5')}}</th>
                      <th>{{translate('lang.adm17')}}</th>
                      <th>{{translate('lang.adm18')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="product in products.data" :key="product.id">

                      <td>{{product.id}}</td>
                      <td>{{product.name}}</td>
                      <td>{{product.description | truncate(30, '...')}}</td>
                      <td>{{product.category.name}}</td>
                      <td>{{product.price}}</td>
                      <!-- <td><img v-bind:src="'/' + product.photo" width="100" alt="product"></td> -->
                      <td>
                        
                        <a href="#" @click="editModal(product)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteProduct(product.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="products" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">{{translate('lang.adm19')}}</h5>
                    <h5 class="modal-title" v-show="editmode">{{translate('lang.adm20')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateProduct() : createProduct()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{translate('lang.adm15')}}</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>{{translate('lang.adm16')}}</label>
                            <input v-model="form.description" type="text" name="description"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                            <has-error :form="form" field="description"></has-error>
                        </div>
                        <div class="form-group">
                            <label>{{translate('lang.adm17')}}</label>
                            <input v-model="form.price" type="text" name="price"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                            <has-error :form="form" field="price"></has-error>
                        </div>
                        <div class="form-group">

                            <label>{{translate('lang.adm5')}}</label>
                            <select class="form-control" v-model="form.category_id">
                              <option 
                                  v-for="(cat,index) in categories" :key="index"
                                  :value="index"
                                  :selected="index == form.category_id">{{ cat }}</option>
                            </select>
                            <has-error :form="form" field="category_id"></has-error>
                        </div>
                        <div class="form-group">
                            <label>{{translate('lang.adm4')}}</label>
                            <vue-tags-input
                              v-model="form.tag"
                              :tags="form.tags"
                              :autocomplete-items="filteredItems"
                              @tags-changed="newTags => form.tags = newTags"
                            />
                            <has-error :form="form" field="tags"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('lang.adm21')}}</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">{{translate('lang.adm22')}}</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">{{translate('lang.adm23')}}</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
      components: {
          VueTagsInput,
        },
        data () {
            return {
                editmode: false,
                products : {},
                form: new Form({
                    id : '',
                    category : '',
                    name: '',
                    description: '',
                    tags:  [],
                    photo: '',
                    category_id: '',
                    price: '',
                    photoUrl: '',
                }),
                categories: [],
              
                tag:  '',
                autocompleteItems: [],
            }
        },
        methods: {

          getResults(page = 1) {

              this.$Progress.start();
              
              axios.get('api/product?page=' + page).then(({ data }) => (this.products = data.data));

              this.$Progress.finish();
          },
          loadProducts(){

            // if(this.$gate.isAdmin()){
              axios.get("api/product").then(({ data }) => (this.products = data.data));
            // }
          },
          loadCategories(){
              axios.get("/api/category/list").then(({ data }) => (this.categories = data.data));
          },
          loadTags(){
              axios.get("/api/tag/list").then(response => {
                  this.autocompleteItems = response.data.data.map(a => {
                      return { text: a.name, id: a.id };
                  });
              }).catch(() => console.warn('Oh. Something went wrong'));
          },
          editModal(product){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(product);
          },
          newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
          },
          createProduct(){
              this.$Progress.start();

              this.form.post('api/product')
              .then((data)=>{
                if(data.data.success){
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                  this.$Progress.finish();
                  this.loadProducts();

                } else {
                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });

                  this.$Progress.failed();
                }
              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },
          updateProduct(){
              this.$Progress.start();
              this.form.put('api/product/'+this.form.id)
              .then((response) => {
                  // success
                  $('#addNew').modal('hide');
                  Toast.fire({
                    icon: 'success',
                    title: response.data.message
                  });
                  this.$Progress.finish();
                      //  Fire.$emit('AfterCreate');

                  this.loadProducts();
              })
              .catch(() => {
                  this.$Progress.fail();
              });

          },
          deleteProduct(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('api/product/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadProducts();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },

        },
        mounted() {
            
        },
        created() {
            this.$Progress.start();

            this.loadProducts();
            this.loadCategories();
            this.loadTags();

            this.$Progress.finish();
        },
        filters: {
            truncate: function (text, length, suffix) {
                return text.substring(0, length) + suffix;
            },
        },
        computed: {
          filteredItems() {
            return this.autocompleteItems.filter(i => {
              return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
          },
        },
    }
</script>
