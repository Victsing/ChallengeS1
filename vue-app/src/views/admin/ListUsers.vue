<template>
  <base-nave-bar color="black" />
  <v-container>
    <v-row>
      <v-col>
        <base-title>Voici la page de gestion des users</base-title>

        <h2>Vous pouvez éditer / ajouter / supprimer un utilisateur</h2>
      </v-col>
    </v-row>
    <v-row class="h-100">
      <v-col>
        <v-card>
          <v-table>
            <thead>
              <tr>
                <th class="text-left">Name</th>
                <th class="text-left">Calories</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ item.name }}</td>
                <td>{{ item.calories }}</td>
              </tr>
            </tbody>
          </v-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { BaseNaveBar, BaseTitle } from '@/components';
</script>
<style scoped>
.container {
  max-width: 1080px;
  margin: 0 auto;
}
</style>