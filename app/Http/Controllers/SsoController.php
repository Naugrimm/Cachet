<?php

namespace CachetHQ\Cachet\Http\Controllers;

use CachetHQ\Cachet\Models\SpEmployees;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Socialite;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

class SsoController extends Controller
{
    private $socialiteDriver = 'securepoint-corp-sso';
    private $loginRedirect = '../../';

    /**
     * Redirect the user to the Rsp authentication page.
     *
     * @param Request $request
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        if (!config('services.securepoint-corp-sso.enabled')) {
            abort(403, "SSO nicht aktiv. Bitte melde dich mit deinen AD-Zugangsdaten an.");
        }
        return Socialite::driver($this->socialiteDriver)
            ->scopes(explode(',', config("services.".$this->socialiteDriver.'.scopes')))
            ->with(["approval_prompt" => "auto"])
            ->redirect();
    }

    /**
     * Obtain the user information from Rsp.
     *
     * @param Request $request
     * @return Factory|RedirectResponse|View
     * @throws AuthorizationException
     */
    public function handleProviderCallback(Request $request)
    {
        if ($request->has("error")) {
            return $this->handleOAuthError($request);
        }

        try {
            $user = Socialite::driver($this->socialiteDriver)->user();

            $spEmployee = $this->findUser($user);

            if($spEmployee == null) {

                $firstname = explode(' ', $user->nickname)[0];
                $lastname = explode(' ', $user->nickname)[1];;

                $spEmployee = SpEmployees::create([
                    'username' => $user->id,
                    'firstname' => $firstname,
                    'lastname' => $lastname
                ]);
            }

            session()->put('sp_employee', $spEmployee->id);
            return redirect()->to($this->loginRedirect);
        } catch (Exception $e) {
            abort(401, $e);
        }
    }

    /**
     * @param $user
     * @return User|bool
     */
    public function findUser($user)
    {
        $spEmployee = SpEmployees::where('username', $user->id)->first();
        return $spEmployee;
    }

    /**
     * @param Request $request
     * @return Factory|View
     * @throws AuthorizationException
     */
    protected function handleOAuthError(Request $request)
    {
        $error = $request->input("error");
        $message = $request->input("message");
        $hint = $request->input("hint");

        switch ($error) {
            case "access_denied":
                return view("errors.generic-exception", [
                    "title" => trans("common.".$error),
                    "message" => trans("oauth.access-denied-explain"),
                    "details" => $message." ".$hint,
                    "previousUrl" => url("/oauth/corp-sso/")
                ]);
                break;
            default:
                throw new AuthorizationException($message." ".$hint." (".$error.")");
        }
    }
}
