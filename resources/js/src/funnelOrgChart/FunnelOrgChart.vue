<template>
	<div>
		<organization-chart :datasource="ds"></organization-chart>
	</div>
</template>

<script>
  import OrganizationChart from 'vue-organization-chart'
  import 'vue-organization-chart/dist/orgchart.css'

  export default {
    components: {
      OrganizationChart
    },
    data () {
      return {
        ds: {
            id: "80faebb1-3841-45a0-b961-97bfa446b73b",
            name: "Funil de vendas",
            children: [
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
        }
      }
    }
  }
</script>
