<template>
    <v-container fluid>
        <v-row dense>
            <v-col cols="12">
                <MainFormGeneral :form="mainForm" :model="object" :onSuccess="Success" :onCancel="Cancel"/>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import MainFormGeneral from '@/components/forms/MainFormGeneral.vue'
export default {
    data: () => ({
        mainForm: [
            {
                field: "text",
                // offset: 0,
                // hidden: false
                nameid: "nombre",
                name: "Nombre",
                type: "text",
                maxlength: "",
                counter: "",
                appendicon: null,
                appendinnericon: null,
                prependicon: null,
                prependinnericon: null,
                prefix: null,
                suffix: null,
                cols: 12
            },
            {
                field: "textarea",
                // offset: 0,
                // hidden: false
                nameid: "descripcion",
                name: "Descripcion",
                rows: 4,
                bgcolor: "",
                color: "",
                counter: "",
                cols: 12
            },
            {
                field: "radiobutton",
                // offset: 0,
                // hidden: false
                nameid: "opcion",                
                name: "Opciones General",
                column: false,
                inline: true,
                items:[
                    { name: "Si", value: 'si' },
                    { name: "No", value: 'no' }
                ],
                cols: 12
            },
            {
                field: "checkbox",
                // offset: 0,
                // hidden: false
                nameid: "multiple",                
                name: "Opciones Multiple",
                inline: true,
                items:[
                    { name: "Si", value: 'si' },
                    { name: "No", value: 'no' }
                ],
                cols: 12
            }
        ],
        object: {}
    }),
    components: {
        MainFormGeneral
    },
    methods: {
        Cancel () {

        },
        Success (object) {
            console.log('Success', object)
        }
    },
}
</script>