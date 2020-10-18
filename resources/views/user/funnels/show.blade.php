@extends('layouts.crud.show', [
    'title' => 'Visualizar Funil',
    'route' => route('funnel.index')
])

@section('page-style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/deltoss/d3-mitch-tree@1.0.2/dist/css/d3-mitch-tree.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/deltoss/d3-mitch-tree@1.0.2/dist/css/d3-mitch-tree-theme-default.min.css">
@endsection

@section('show-content')
<section id="visualisation">
</section>
@endsection

@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/d3-mitch-tree@1.1.2/dist/js/d3-mitch-tree.min.js"></script>
    <script>
        $.get("/funnel/{{ $funnel->id }}/show/json")
            .then(res => {
                var data = res.data
                console.log(res)

                var treePlugin = new d3.mitchTree.boxedTree()
                    .setData(res)
                    .setElement(document.getElementById("visualisation"))
                    .setIdAccessor(function(data) {
                        return data.id;
                    })
                    .setChildrenAccessor(function(data) {
                        return data.children;
                    })
                    .setBodyDisplayTextAccessor(function(data) {
                        return data.description;
                    })
                    .setTitleDisplayTextAccessor(function(data) {
                        return data.name;
                    })
                    //.setOrientation('topToBottom')
                    .initialize();

            })

        /* var data = {
            id: "80faebb1-3841-45a0-b961-97bfa446b73b",
            name: "",
            description: "Funil de vendas",
            children: [
                {
                    id: "7ca13b97-1a5b-4267-808d-43a5566a5352",
                    name: "",
                    description: "Compra Finalizada",
                    children: [
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "",
                            description: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "",
                            description: "Enviar E-mail",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "",
                            description: "Enviar Whatsapp",
                        },
                    ]
                },
                {
                    id: "7ca13b97-1a5b-4267-808d-43a5566a5352",
                    name: "Compra Finalizada",
                    children: [
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        }
                    ]
                },
                {
                    id: "7ca13b97-1a5b-4267-808d-43a5566a5352",
                    name: "Compra Finalizada",
                    children: [
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                        {
                            id: "4ccf2c47-ff26-4657-9345-75cb36a8e1ab",
                            name: "Enviar SMS",
                        },
                    ]
                },
                {
                    id: "e6614610-1aa1-4ab2-a521-de6e2047bee7",
                    name: "Imprimiu Boleto",
                    children: [
                        {
                            id: "243ba3d7-4a1c-4186-b756-b8cc1e2638af",
                            name: "Enviar SMS",
                        }
                    ]
                }
            ]
        };



        var treePlugin = new d3.mitchTree.boxedTree()
				.setData(data)
				.setElement(document.getElementById("visualisation"))
				.setIdAccessor(function(data) {
					return data.id;
				})
				.setChildrenAccessor(function(data) {
					return data.children;
				})
				.setBodyDisplayTextAccessor(function(data) {
					return data.description;
				})
				.setTitleDisplayTextAccessor(function(data) {
					return data.name;
				})
				//.setOrientation('topToBottom')
				.initialize(); */
    </script>
@endsection
