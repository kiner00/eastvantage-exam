import { useEffect, useState } from "react";
import { useHistory } from "react-router-dom";
import api from "../api/axios";
import { Role } from "../types";

const AddUser = () => {
  const history = useHistory();

  const [fullName, setFullName] = useState("");
  const [email, setEmail] = useState("");
  const [roleIds, setRoleIds] = useState<number[]>([]);
  const [roles, setRoles] = useState<Role[]>([]);
  const [loadingRoles, setLoadingRoles] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchRoles = async () => {
      try {
        const res = await api.get("/roles");

        if (Array.isArray(res.data.data)) {
          setRoles(res.data.data);
        } else if (Array.isArray(res.data)) {
          setRoles(res.data);
        } else {
          setError("Invalid roles response.");
          setRoles([]);
        }
      } catch (e) {
        console.error("Failed to fetch roles", e);
        setError("Failed to load roles.");
        setRoles([]);
      } finally {
        setLoadingRoles(false);
      }
    };

    fetchRoles();
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    if (roleIds.length === 0) {
      setError("Please select at least one role.");
      return;
    }

    try {
      await api.post("/users", {
        full_name: fullName,
        email,
        role_ids: roleIds,
      });

      history.push("/");
    } catch (err: any) {
      setError(err.response?.data?.message || "Failed to create user.");
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100 px-4">
      <div className="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 className="text-2xl font-semibold mb-6 text-center">
          Add New User
        </h2>

        {error && <div className="text-red-600 mb-4">{error}</div>}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block font-medium mb-1">Full Name</label>
            <input
              type="text"
              value={fullName}
              onChange={(e) => setFullName(e.target.value)}
              required
              className="w-full border rounded px-3 py-2"
            />
          </div>

          <div>
            <label className="block font-medium mb-1">Email Address</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              className="w-full border rounded px-3 py-2"
            />
          </div>

          <div>
            <label className="block font-medium mb-1">Role</label>
            {loadingRoles ? (
              <p className="text-gray-500">Loading roles...</p>
            ) : (
              <select
                multiple
                value={roleIds.map(String)}
                onChange={(e) =>
                  setRoleIds(
                    Array.from(e.target.selectedOptions, (option) =>
                      Number(option.value)
                    )
                  )
                }
                required
                className="w-full border rounded px-3 py-2 h-32"
              >
                {roles.map((role) => (
                  <option key={role.id} value={role.id}>
                    {role.name}
                  </option>
                ))}
              </select>
            )}
          </div>

          <div className="text-center">
            <button
              type="submit"
              className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
              disabled={loadingRoles}
            >
              Create User
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default AddUser;
