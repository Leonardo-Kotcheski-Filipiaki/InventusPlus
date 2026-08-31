import type { Person } from './Person';
import type { User } from './User';

export interface Auth {
    user?: User;
    person?: Person;
}

export interface Flash {
    success?: string | null;
    error?: string | null;
    warning?: string | null;
    info?: string | null;
}
