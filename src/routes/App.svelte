<script lang="ts">
     import { Router, Link, Route } from "svelte-routing";
     import BookManagement from "../lib/components/BookManagement.svelte";
     import UserManagement from "../lib/components/UserManagement.svelte";
     import BorrowerDashboard from "../lib/components/BorrowerDashboard.svelte";
   
     export let url = "";
   </script>
   
   <Router {url}>
     <nav class="bg-blue-600 p-4 text-white">
       <ul class="flex space-x-4">
         <li><Link to="/">Home</Link></li>
         <li><Link to="/books">Books</Link></li>
         <li><Link to="/users">Users</Link></li>
         <li><Link to="/borrower">Borrower</Link></li>
       </ul>
     </nav>
   
     <main class="container mx-auto mt-8 p-4">
       <Route path="/books" component={BookManagement} />
       <Route path="/users" component={UserManagement} />
       <Route path="/borrower" component={BorrowerDashboard} />
       <Route path="/">
         <h1 class="text-3xl font-bold mb-4">Welcome to Library Book Management System</h1>
         <p>Please select a section from the navigation menu above.</p>
       </Route>
     </main>
   </Router>
   
   
   