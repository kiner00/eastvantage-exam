import React, { useEffect, useState } from "react";
import { useHistory } from "react-router-dom";
import api from "../api/axios";
import { User } from "../types";

const UserList: React.FC = () => {
  const [users, setUsers] = useState<User[]>([]);
  const history = useHistory();

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = async () => {
    try {
      const response = await api.get("/users");
      setUsers(response.data.data);
    } catch (error) {
      console.error("Failed to fetch users:", error);
    }
  };

  const handleAddUser = () => {
    history.push("/add-user");
  };

  return (
    <div className="flex justify-center mt-10">
      <div className="w-full max-w-4xl bg-white shadow rounded p-6">
        <div className="flex justify-between items-center mb-2">
          <h2 className="text-2xl font-bold text-center w-full">
            User Management System
          </h2>
          <button
            onClick={handleAddUser}
            className="ml-auto bg-blue-600 text-white px-3 py-3 text-sm rounded hover:bg-blue-700 whitespace-nowrap"
          >
            Add User
          </button>
        </div>

        <table className="min-w-full table-auto border border-gray-200">
          <thead className="bg-indigo-700 text-white">
            <tr>
              <th className="px-4 py-2 text-left">Full Name</th>
              <th className="px-4 py-2 text-left">Email</th>
              <th className="px-4 py-2 text-left">Role</th>
            </tr>
          </thead>
          <tbody>
            {users.map((user) => (
              <tr key={user.id} className="border-t">
                <td className="px-4 py-2">{user.full_name}</td>
                <td className="px-4 py-2">{user.email}</td>
                <td className="px-4 py-2">
                  {user.roles.map((role) => role.name).join(", ")}
                </td>
              </tr>
            ))}
            {users.length === 0 && (
              <tr>
                <td colSpan={3} className="text-center py-4 text-gray-500">
                  No users found.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default UserList;
