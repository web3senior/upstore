// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import {ILSP7DigitalAsset as ILSP7} from "@lukso/lsp-smart-contracts/contracts/LSP7DigitalAsset/ILSP7DigitalAsset.sol";
import "@openzeppelin/contracts/utils/Counters.sol";
import "@openzeppelin/contracts/utils/Strings.sol";
import {Base64} from "@openzeppelin/contracts/utils/Base64.sol";
import "./_event.sol";
import "./_error.sol";
import "./_pausable.sol";
import "./_ownable.sol";

/// @title DirectDonation
/// @author Aratta Labs
/// @notice Direct donation mechanism
/// @dev You will find the deployed contract addresses in the repo
/// @custom:emoji 💸
/// @custom:security-contact atenyun@gmail.com
contract DirectDonation is Ownable(msg.sender), Pausable {
    using Counters for Counters.Counter;
    Counters.Counter public _donateCounter;
    Counters.Counter public _tokenCounter;
    uint8 fee = 2;

    struct DonationStruct {
        address donator;
        bytes32 tokenId;
        bytes32 appId;
        uint256 amount;
        address to;
        uint256 dt;
    }

    struct TokenStruct {
        address addr;
        bool pause;
        uint256 dt;
    }

    struct TokenExportStruct {
        bytes32 id;
        address addr;
        bool pause;
        uint256 dt;
    }

    mapping(bytes32 => DonationStruct) public donation;
    mapping(bytes32 => TokenStruct) public token;

    constructor() {
        _tokenCounter.increment();
        token[bytes32(_tokenCounter.current())] = TokenStruct(address(0), false, block.timestamp);
    }

    ///@notice Get token list
    function getTokenList() public view returns (TokenExportStruct[] memory list) {
        TokenExportStruct[] memory result = new TokenExportStruct[](_tokenCounter.current());
        for (uint256 i = 0; i < _tokenCounter.current(); i++) result[i] = TokenExportStruct(bytes32(i + 1), token[bytes32(i + 1)].addr, token[bytes32(i + 1)].pause, token[bytes32(i + 1)].dt);
        return result;
    }

    ///@notice Get token
    function getToken(address _addr) public view returns (TokenStruct[] memory list) {
        TokenStruct[] memory result = new TokenStruct[](_tokenCounter.current());

        uint256 counter = 0;
        for (uint256 i = 0; i < _tokenCounter.current(); i++) {
            if (token[bytes32(i + 1)].addr == _addr) {
                result[counter] = token[bytes32(i + 1)];
                counter++;
            }
        }

        return result;
    }

    ///@notice Get token list
    function getDonationList() public view returns (DonationStruct[] memory list) {
        DonationStruct[] memory result = new DonationStruct[](_donateCounter.current());
        for (uint256 i = 0; i < _donateCounter.current(); i++) result[i] = donation[bytes32(i + 1)];
        return result;
    }

    ///@notice Get donation list, filter by donator
    function getDonation(address _donator) public view returns (DonationStruct[] memory list) {
        DonationStruct[] memory result = new DonationStruct[](_donateCounter.current());

        uint256 counter = 0;
        for (uint256 i = 0; i < _donateCounter.current(); i++) {
            if (donation[bytes32(i + 1)].donator == address(_donator)) {
                result[counter] = donation[bytes32(i + 1)];
                counter++;
            }
        }

        return result;
    }

    ///@notice Get donation list, filter by tokenId
    function getDonationByToekn(bytes32 _tokenId) public view returns (DonationStruct[] memory list) {
        DonationStruct[] memory result = new DonationStruct[](_donateCounter.current());

        uint256 counter = 0;
        for (uint256 i = 0; i < _donateCounter.current(); i++) {
            if (donation[bytes32(i + 1)].tokenId == _tokenId) {
                result[counter] = donation[bytes32(i + 1)];
                counter++;
            }
        }

       return result;
    }

    ///@notice Get donation list, filter by tokenId
    function getDonationByAppId(bytes32 _appId) public view returns (DonationStruct[] memory list) {
        DonationStruct[] memory result = new DonationStruct[](_donateCounter.current());

        uint256 counter = 0;
        for (uint256 i = 0; i < _donateCounter.current(); i++) {
            if (donation[bytes32(i + 1)].appId == _appId) {
                result[counter] = donation[bytes32(i + 1)];
                counter++;
            }
        }

        return result;
    }

    ///@notice Donate
    function donate(
        bytes32 _appId,
        address _to,
        uint256 _amount,
        bool _force,
        bytes memory _data,
        bytes32 _tokenId
    ) public payable whenNotPaused returns (bool) {
        if (_tokenId == bytes32(uint256(1))) {
            if (msg.value == 0) revert InsufficientBalance(msg.value);
            (bool success, ) = _to.call{value: calcPercentage(_amount, 100 - fee)}("");
            require(success, "Failed to send Ether to the manager");
        } else {
            uint256 authorizedAmount = ILSP7(token[_tokenId].addr).authorizedAmountFor(address(this), _msgSender());
            if (authorizedAmount != _amount) revert NotAuthorizedAmount(_amount, authorizedAmount);

            ILSP7(token[_tokenId].addr).transfer(_msgSender(), address(_to), calcPercentage(_amount, 100 - fee), _force, _data);
            ILSP7(token[_tokenId].addr).transfer(_msgSender(), owner(), calcPercentage(_amount, fee), _force, _data);
        }

        _donateCounter.increment();
        donation[bytes32(_donateCounter.current())] = DonationStruct(_msgSender(), _tokenId, _appId, _amount, _to, block.timestamp);

        // Log donated
        emit Donated(_msgSender(), _to, _amount, _tokenId, _appId);

        return true;
    }

    ///@notice calcPercentage percentage
    ///@param amount The total amount
    ///@param bps The precentage
    ///@return percentage
    function calcPercentage(uint256 amount, uint256 bps) public pure returns (uint256) {
        require((amount * bps) >= 100);
        return (amount * bps) / 100;
    }

    /// @notice Add a token
    function addToken(address _addr) public onlyOwner returns (bytes32) {
        _tokenCounter.increment();
        token[bytes32(_tokenCounter.current())] = TokenStruct(address(_addr), false, block.timestamp);

        // Log token added
        emit TokenAdded(bytes32(_tokenCounter.current()), address(_addr), _msgSender());
        return bytes32(_tokenCounter.current());
    }

    /// @notice Delete a token
    function deleteToken(bytes32 _tokenId) public onlyOwner returns (uint256) {
        delete token[_tokenId];
        _tokenCounter.decrement();
        
        // Log token removed
        emit TokenRemoved(bytes32(_tokenCounter.current()), _msgSender());
        return _tokenCounter.current();
    }

    /// @notice Update token
    function updateToken(
        bytes32 _tokenId,
        address _addr,
        bool _pause
    ) public onlyOwner returns (bool) {
        token[_tokenId].addr = _addr;
        token[_tokenId].pause = _pause;
        
        // Log token updated
        emit TokenUpdated(_tokenId, _addr, _msgSender());
        return true;
    }

    /// @notice Update fee
    function updateToken(uint8 _fee) public onlyOwner returns (bool) {
        fee = _fee;
        emit feeUpdated(_fee);
        return true;
    }

    ///@notice Withdraw the balance from this contract and transfer it to the owner's address
    function withdraw() public onlyOwner {
        uint256 amount = address(this).balance;
        (bool success, ) = owner().call{value: amount}("");
        require(success, "Failed");
    }

    ///@notice Transfer balance from this contract to input address
    function transferBalance(address payable _to, uint256 _amount) public onlyOwner {
        // Note that "to" is declared as payable
        (bool success, ) = _to.call{value: _amount}("");
        require(success, "Failed");
    }

    /// @notice Return the balance of this contract
    function getBalance() public view returns (uint256) {
        return address(this).balance;
    }

    /// @notice Pause
    function pause() public onlyOwner {
        _pause();
    }

    /// @notice Unpause
    function unpause() public onlyOwner {
        _unpause();
    }
}
