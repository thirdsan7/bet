<?php

namespace App\Exceptions;

use Throwable;
use App\Responses\FunkyResponse;
use App\Responses\EyeconResponse;
use App\Responses\ZirconResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Exceptions\Player\BetLimitException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\Game\GameIDNotFoundException;
use App\Exceptions\General\InvalidInputException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\Player\MaxWinningLimitException;
use App\Exceptions\Player\BalanceNotEnoughException;
use App\Exceptions\Player\PlayerNotLoggedInException;
use App\Exceptions\Game\SystemUnderMaintenanceException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\Transaction\RoundAlreadyExistsException;
use App\Exceptions\Transaction\RoundAlreadySettledException;
use App\Exceptions\Transaction\RoundAlreadyCancelledException;

class Handler extends ExceptionHandler
{
    const DUPLICATE_ENTRY = 1062;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    private $zirconResponse;
    private $funkyResponse;
    private $eyeconResponse;
    private $response;

    public function __construct(ZirconResponse $zirconResponse, FunkyResponse $funkyResponse, EyeconResponse $eyeconResponse)
    {
        $this->zirconResponse = $zirconResponse;
        $this->funkyResponse = $funkyResponse;
        $this->eyeconResponse = $eyeconResponse;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        try {
            DB::rollBack();

            if($request->is('Funky/*'))
                $this->response = $this->funkyResponse;
            elseif ($request->is('api/eyecon'))
                $this->response = $this->eyeconResponse;
            else
                $this->response = $this->zirconResponse;

            throw $exception;
        } catch(InvalidInputException $e) {

            return $this->response->invalidInput($e->getMessage());
        } catch(GameIDNotFoundException $e) {
            
            return $this->response->invalidGameID();
        } catch(PlayerNotLoggedInException $e) {
            
            return $this->response->playerNotLoggedIn();
        } catch(SystemUnderMaintenanceException $e) {
            
            return $this->response->systemUnderMaintenance();
        } catch(QueryException $e) {
            
            if($e->errorInfo[1] === self::DUPLICATE_ENTRY) {
                return $this->response->betAlreadyExists();
            } else {    
                return $this->response->somethingWentWrong($e->getMessage());
            }
            
        } catch (BalanceNotEnoughException $e) {
            
            return $this->response->balanceNotEnough();
        } catch (RoundAlreadyExistsException $e) {

            return $this->response->betAlreadyExists();
        } catch (MaxWinningLimitException $e) {

            return $this->response->maxWinningExceed();
        } catch (BetLimitException $e) {

            return $this->response->betLimitExceed();
        } catch(RoundAlreadySettledException $e) {
            
            return $this->response->betAlreadySettled();
        } catch(RoundAlreadyCancelledException $e) {
            
            return $this->response->betAlreadyCancelled();
        } catch (\Exception $e) {
            
            return $this->response->somethingWentWrong($e->getMessage());
        }
        
        // return parent::render($request, $exception);
    }
}
