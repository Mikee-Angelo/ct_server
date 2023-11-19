import { useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import {
    Button,
    FormControl,
    FormLabel,
    FormErrorMessage,
    FormHelperText,
    Input,
} from '@chakra-ui/react'
export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();

        post(route('login'));
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <form onSubmit={submit}>

                <FormControl isRequired isInvalid={errors.email}>
                    <FormLabel>Email address</FormLabel>
                    <Input
                        type='email'
                        name='email'
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)} />
                    {!errors.email ? (<FormHelperText>Do not share email to others</FormHelperText>) : (<FormErrorMessage>{errors.email}</FormErrorMessage>)}

                </FormControl>

                <FormControl isRequired mt={4} isInvalid={errors.password}>
                    <FormLabel>Password</FormLabel>
                    <Input
                        type='password'
                        name='password'
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)} />
                    <FormErrorMessage>{errors.password}</FormErrorMessage>
                </FormControl>


                <div className="flex items-center justify-end mt-4">
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            Forgot your password?
                        </Link>
                    )}

                    <Button
                        type='submit'
                        colorScheme='green'
                        isLoading={processing}
                        isDisabled={processing}
                        ml={4}>Login
                    </Button>
                </div>
            </form>
        </GuestLayout>
    );
}
