<template>
	<div class ="container">
		<div class="row">
			<div class="col-md-4" v-for="todo in todos">
				<div class="panel panel-default">
					<div class="panel-heading">{{todo.title}}</div>

					<div class="panel-body">
						{{todo.main}}
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
        created() {
            this.fetchTodos()
        },
        data() {
            return {
                todos: []
            }
        },
        methods: {
            fetchTodos() {
                axios.get('/api/gettodo')
                .then(res =>  {
                    this.todos = res.data;
                })
            }
        }
    }
</script>
	