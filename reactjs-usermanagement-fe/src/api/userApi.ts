import api from "./axios";
import { User } from "../types";

export const fetchUsers = async (): Promise<User[]> => {
  const response = await api.get("/users");
  return response.data.data;
};

export const createUser = async (user: {
  full_name: string;
  email: string;
  role_ids: number[];
}): Promise<User> => {
  const response = await api.post("/users", user);
  return response.data.data;
};
