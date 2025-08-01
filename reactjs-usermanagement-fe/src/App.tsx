import React from "react";
import { BrowserRouter, Switch, Route } from "react-router-dom";
import UsersPage from "./pages/UsersPage";
import AddUser from "./pages/AddUser";

const App: React.FC = () => {
  return (
    <BrowserRouter>
      <div className="min-h-screen bg-gray-100 text-gray-800">
        <header className="bg-white shadow p-4 mb-6">
          <h1 className="text-2xl font-bold text-center">
            User Management System
          </h1>
        </header>

        <main className="container mx-auto px-4">
          <Switch>
            <Route exact path="/" component={UsersPage} />
            <Route path="/add-user" component={AddUser} />
          </Switch>
        </main>
      </div>
    </BrowserRouter>
  );
};

export default App;
